# Probar Stripe localmente

Este documento explica cómo probar flujos de pago sin acceso al Stripe CLI o sin exponer webhooks públicos.

1. Configura tus claves de prueba en `.env`:

```
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=
STRIPE_CURRENCY=usd
```

2. Si tienes Stripe CLI instalado, ejecuta:

```
stripe login
stripe listen --forward-to http://localhost:8000/stripe/webhook
```

3. Si no tienes Stripe CLI, usa el comando Artisan incluido para simular eventos de webhook localmente:

```
php artisan stripe:simulate checkout.session.completed --invoice=123 --paid
```

Esto actualizará la factura `123` y marcará su `status` como `paid`, disparando los `Observers` y generando un registro de auditoría similar al comportamiento del webhook real.

4. Scripts útiles:

- (Opcional) Si necesitas automatizar despliegues o levantar servicios, crea tus propios scripts locales.
