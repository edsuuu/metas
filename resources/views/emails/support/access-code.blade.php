<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Código de acesso aos seus chamados - Everest</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap');
    body {
        font-family: 'Manrope', sans-serif;
        background-color: #f0f4f2;
        color: #111815;
        margin: 0;
        padding: 0;
        -webkit-font-smoothing: antialiased;
    }
</style>
</head>
<body style="background-color: #f0f4f2; color: #111815; font-family: 'Manrope', sans-serif; margin: 0; padding: 48px 16px; min-height: 100vh;">
    <div style="max-width: 600px; margin: 0 auto;">
        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 32px;">
            <div style="display: inline-flex; align-items: center; justify-content: center; gap: 12px;">
                <div style="width: 40px; height: 40px; color: #13ec92;">
                    <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" width="40" height="40">
                        <path clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V44Z" fill-rule="evenodd"></path>
                    </svg>
                </div>
                <h2 style="color: #111815; font-size: 24px; font-weight: 800; margin: 0;">Everest</h2>
            </div>
        </div>

        <!-- Main Card -->
        <div style="background-color: #ffffff; border-radius: 32px; border: 1px solid #dbe6e1; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden;">
            <div style="padding: 32px 48px;">
                <h1 style="font-size: 24px; font-weight: 700; color: #111815; margin: 0 0 24px 0; text-align: center;">
                    Verifique seu acesso
                </h1>
                <p style="color: #4b5563; margin: 0 0 32px 0; line-height: 1.6; text-align: center;">
                    Olá! Recebemos uma solicitação para visualizar seus chamados no Everest. Por segurança, precisamos validar sua identidade antes de exibir a lista de solicitações.
                </p>

                <!-- Code Box -->
                <div style="background-color: #f6f8f7; border-radius: 16px; padding: 32px; margin-bottom: 32px; text-align: center; border: 2px dashed #dbe6e1;">
                    <span style="font-size: 12px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 16px;">Código de Verificação</span>
                    <div style="font-size: 48px; font-weight: 900; color: #13ec92; letter-spacing: 0.2em; line-height: 1;">
                        {{ $code }}
                    </div>
                </div>

                <!-- Info Box -->
                <div style="background-color: #eff6ff; border-radius: 12px; padding: 16px; display: flex; align-items: flex-start; gap: 12px;">
                    <div style="color: #3b82f6; font-size: 20px; line-height: 1;">ℹ</div>
                    <p style="font-size: 14px; color: #1d4ed8; margin: 0;">
                        Este código expira em 15 minutos. Se você não solicitou este acesso, ignore este e-mail.
                    </p>
                </div>
            </div>

            <!-- Footer Note -->
            <div style="background-color: #f9fafb; padding: 24px 32px; border-top: 1px solid #dbe6e1; text-align: center;">
                <p style="font-size: 12px; color: #6b7280; margin: 0;">
                    Este é um e-mail automático enviado pelo Everest. Por favor, não responda a esta mensagem.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 32px;">
            <p style="font-size: 12px; color: #9ca3af; margin: 0 0 16px 0;">© 2024 Everest Technologies Inc. Todos os direitos reservados.</p>
            <div style="display: flex; justify-content: center; gap: 24px;">
                <a href="{{ route('support.index') }}" style="font-size: 12px; color: #6b7280; text-decoration: underline;">Central de Ajuda</a>
                <a href="{{ route('legal.privacy') }}" style="font-size: 12px; color: #6b7280; text-decoration: underline;">Privacidade</a>
                <a href="{{ route('legal.terms.index') }}" style="font-size: 12px; color: #6b7280; text-decoration: underline;">Termos de Uso</a>
            </div>
        </div>
    </div>
</body>
</html>
