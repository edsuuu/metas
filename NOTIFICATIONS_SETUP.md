# Configuração do Sistema de Notificações

Guia para configurar o sistema de notificações de ofensivas em produção.

---

## 1. Configurar Email (Obrigatório)

Configure um serviço de email real (recomendo Amazon SES, Mailgun ou Resend).

### Exemplo com Amazon SES:

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=sua_key
AWS_SECRET_ACCESS_KEY=seu_secret
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS=noreply@seudomain.com
MAIL_FROM_NAME="Everest"
```

### Exemplo com Mailgun:

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.seudomain.com
MAILGUN_SECRET=sua_api_key
MAIL_FROM_ADDRESS=noreply@seudomain.com
MAIL_FROM_NAME="Everest"
```

---

## 2. Configurar Scheduler (Cron)

O scheduler precisa rodar a cada minuto para enviar os lembretes nos horários certos.

### Adicionar no crontab do servidor:

```bash
crontab -e
```

Adicionar a linha:

```cron
* * * * * cd /caminho/para/metas && php artisan schedule:run >> /dev/null 2>&1
```

### Verificar se está funcionando:

```bash
php artisan schedule:list
```

Deve mostrar:

```
09:00  php artisan streaks:remind --period=morning
21:00  php artisan streaks:remind --period=evening
```

---

## 3. Configurar Queue Worker

As notificações são enviadas em background. Configure um supervisor para manter o worker rodando.

### Exemplo com Supervisor:

```bash
sudo apt install supervisor
```

Criar arquivo `/etc/supervisor/conf.d/metas-worker.conf`:

```ini
[program:metas-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /caminho/para/metas/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/caminho/para/metas/storage/logs/worker.log
stopwaitsecs=3600
```

Reiniciar supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start metas-worker:*
```

---

## 4. Testar Manualmente

### Testar comando completo:

```bash
# Lembrete da manhã
php artisan streaks:remind --period=morning

# Lembrete da noite
php artisan streaks:remind --period=evening
```

### Verificar logs:

```bash
tail -f storage/logs/laravel.log
```

---

## 5. (Futuro) Web Push Notifications

Quando quiser ativar Web Push, adicione ao `.env`:

```env
VAPID_PUBLIC_KEY=sua_chave_publica
VAPID_PRIVATE_KEY=sua_chave_privada
VAPID_SUBJECT=mailto:seu@email.com
```

Gerar chaves:

```bash
composer require minishlink/web-push
php artisan tinker
>>> $vapid = Minishlink\WebPush\VAPID::createVapidKeys();
>>> echo "VAPID_PUBLIC_KEY=" . $vapid['publicKey'] . "\n";
>>> echo "VAPID_PRIVATE_KEY=" . $vapid['privateKey'] . "\n";
```

---

## Checklist de Deploy

- [ ] Serviço de email configurado (SES, Mailgun, etc)
- [ ] Cron do scheduler configurado (`* * * * *`)
- [ ] Supervisor configurado para queue worker
- [ ] Rodar migrations: `php artisan migrate`
- [ ] Testado envio manual de notificação
- [ ] (Opcional) VAPID configurado para Web Push
