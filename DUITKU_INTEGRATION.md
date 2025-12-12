# Duitku Payment Integration Guide

## 🔧 Configuration

### 1. Environment Variables

Add to `.env`:

```env
DUITKU_SANDBOX=true  # Set to false for production
```

### 2. System Settings

Login sebagai superadmin: `/superadmin/settings`

Configure:

-   **Payment Gateway**: duitku
-   **Duitku Merchant Code**: Your merchant code from Duitku
-   **Duitku API Key**: Your API key from Duitku
-   **Duitku Callback URL**: `https://your-domain.com/duitku/callback`

## 📋 Payment Flow

### Owner Side

1. Choose subscription plan (Monthly/Yearly)
2. Select "Duitku" as payment method
3. Click Subscribe → Redirected to Duitku payment page
4. Choose payment channel (Bank Transfer, E-wallet, etc.)
5. Complete payment
6. Return to dashboard (auto-approved if successful)

### Duitku Callback

-   Duitku will send callback to: `/duitku/callback`
-   System verifies signature
-   Updates subscription status automatically
-   Payment successful → Status: active, Payment: paid
-   Payment failed → Status: cancelled, Payment: failed

## 🔐 Signature Generation

### Request Signature (Create Invoice)

```
MD5(merchantCode + merchantOrderId + paymentAmount + apiKey)
```

### Callback Signature (Verification)

```
MD5(merchantCode + merchantOrderId + resultCode + apiKey)
```

## 📡 API Endpoints

### Sandbox

```
Base URL: https://sandbox.duitku.com/webapi/api
```

### Production

```
Base URL: https://passport.duitku.com/webapi/api
```

## 🧪 Testing

### Sandbox Mode

1. Set `DUITKU_SANDBOX=true` in `.env`
2. Use test credentials from Duitku dashboard
3. Test payment with dummy card numbers

### Available Payment Methods

-   **VC**: Virtual Account (All Banks)
-   **BC**: Credit Card
-   **M2**: Mandiri Clickpay
-   **VA**: Virtual Account (BCA, BNI, BRI, Permata)
-   **I1**: Indomaret
-   **A1**: Alfamart
-   **FT**: Facilitated Transfer
-   **OV**: OVO
-   **DA**: DANA
-   **SP**: ShopeePay
-   **LF**: LinkAja

## 📊 Database Fields

### subscriptions table

-   `merchant_order_id`: Unique order ID (SUB-{id}-{timestamp})
-   `duitku_reference`: Duitku reference number
-   `payment_method`: 'duitku'
-   `payment_status`: 'paid' (auto after successful payment)
-   `status`: 'active' (auto-approved)
-   `approved_at`: Auto-set on successful payment
-   `approved_by`: 1 (System)

## 🔄 Callback Handling

### Success Response (resultCode: 00)

```json
{
    "merchantCode": "D1234",
    "merchantOrderId": "SUB-123-1234567890",
    "reference": "DK12345678",
    "resultCode": "00",
    "resultMessage": "SUCCESS",
    "signature": "abc123..."
}
```

### Failed Response

```json
{
    "merchantCode": "D1234",
    "merchantOrderId": "SUB-123-1234567890",
    "resultCode": "01",
    "resultMessage": "FAILED",
    "signature": "abc123..."
}
```

## 🚨 Important Notes

1. **Callback URL must be publicly accessible** - Localhost won't work
2. **Use ngrok or similar** for local testing:

    ```bash
    ngrok http 8000
    # Use: https://xxxx.ngrok.io/duitku/callback
    ```

3. **Signature verification is mandatory** - Prevent fraud

4. **Expiry Period**: Default 1440 minutes (24 hours)

5. **Auto-approval**: Duitku payments are auto-approved on successful callback

6. **No manual intervention needed** - Unlike manual transfer

## 🔍 Troubleshooting

### Payment not auto-approved?

-   Check callback URL is correct
-   Verify signature calculation
-   Check logs: `storage/logs/laravel.log`
-   Ensure callback endpoint is accessible

### Signature mismatch?

-   Verify API key is correct
-   Check merchantCode matches
-   Ensure signature algorithm is MD5
-   Parameter order must be exact

### Callback not received?

-   Verify callback URL is publicly accessible
-   Check firewall/security settings
-   Test with ngrok for local development
-   Contact Duitku support if issue persists

## 📝 Code Example

### Create Payment

```php
// User clicks "Subscribe with Duitku"
POST /subscription/subscribe
{
    "plan": "monthly",
    "payment_method": "duitku"
}

// System creates subscription (status: pending)
// Redirects to DuitkuController@createPayment
// Calls Duitku API to generate payment link
// Redirects user to Duitku payment page
```

### Callback Processing

```php
// Duitku sends callback
POST /duitku/callback
{
    "merchantCode": "D1234",
    "merchantOrderId": "SUB-123-1234567890",
    "reference": "DK12345678",
    "resultCode": "00",
    "signature": "..."
}

// System verifies signature
// Updates subscription:
// - status: active
// - payment_status: paid
// - approved_at: now()
// - approved_by: 1 (System)
```

### User Return

```php
// User clicks "Back to Merchant" on Duitku
GET /duitku/return?merchantOrderId=SUB-123-1234567890

// System shows success message
// Redirects to dashboard
```

## ✅ Testing Checklist

-   [ ] Sandbox credentials configured
-   [ ] Callback URL accessible
-   [ ] Create payment successful
-   [ ] Redirected to Duitku page
-   [ ] Complete payment (sandbox)
-   [ ] Callback received and processed
-   [ ] Subscription auto-approved
-   [ ] User redirected to dashboard
-   [ ] Access granted immediately

## 🎉 Production Deployment

1. Get production credentials from Duitku
2. Update system settings with production API key
3. Set `DUITKU_SANDBOX=false`
4. Test with small amount first
5. Monitor callbacks and logs
6. Enable for all users

## 📞 Support

Duitku Support:

-   Website: https://duitku.com
-   Email: support@duitku.com
-   Phone: +62 21 8065 0088
