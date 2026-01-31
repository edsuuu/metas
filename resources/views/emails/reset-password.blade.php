<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Redefinição de senha Everest</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap');
        body {
            font-family: 'Manrope', sans-serif;
            background-color: #f6f8f7;
            color: #111815;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>
<body style="background-color: #f6f8f7; color: #111815; font-family: 'Manrope', sans-serif; margin: 0; padding: 0; min-height: 100vh;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f6f8f7; width: 100%; height: 100%;">
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <!-- Email Container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 32px 32px 16px 32px; text-align: center;">
                            <div style="display: inline-flex; align-items: center; justify-content: center;">
                                <div style="width: 40px; height: 40px; color: #13ec92; display: inline-block; vertical-align: middle; margin-right: 8px;">
                                    <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" width="40" height="40">
                                        <path clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V44Z" fill-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span style="color: #111815; font-size: 24px; font-weight: 800; letter-spacing: -0.025em; display: inline-block; vertical-align: middle;">Everest</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 24px 32px;">
                            <div style="margin-bottom: 32px; text-align: left;">
                                <h1 style="color: #111815; font-size: 24px; font-weight: 700; margin: 0 0 16px 0;">Olá!</h1>
                                <p style="color: #4b5563; font-size: 16px; line-height: 1.625; margin: 0 0 16px 0;">
                                    Recebemos uma solicitação para redefinir a senha da sua conta no <strong>Everest</strong>. Clique no botão abaixo para escolher uma nova senha e retomar sua jornada rumo às suas metas.
                                </p>
                                <p style="color: #4b5563; font-size: 16px; line-height: 1.625; margin: 0;">
                                    Este link é válido por 60 minutos.
                                </p>
                            </div>

                            <!-- CTA Button -->
                            <div style="text-align: center; padding: 24px 0;">
                                <a href="{{ $url }}" style="display: inline-block; background-color: #13ec92; color: #111815; padding: 16px 40px; border-radius: 9999px; font-weight: 700; font-size: 16px; text-decoration: none; box-shadow: 0 10px 15px -3px rgba(19, 236, 146, 0.2);">
                                    Redefinir minha senha
                                </a>
                            </div>

                            <!-- Warning Box -->
                            <div style="margin-top: 32px; padding-top: 32px; border-top: 1px solid #f3f4f6;">
                                <div style="background-color: #f9fafb; padding: 16px; border-radius: 16px; display: table; width: 100%;">
                                    <div style="display: table-cell; vertical-align: top; width: 24px; color: #9ca3af; padding-right: 12px;">
                                        <span style="font-family: sans-serif; font-size: 20px;">ℹ</span>
                                    </div>
                                    <div style="display: table-cell; vertical-align: top;">
                                        <p style="font-size: 14px; color: #6b7280; line-height: 1.625; margin: 0;">
                                            Se você não solicitou a troca de senha, por favor ignore este e-mail. Sua senha permanecerá a mesma e sua conta continuará segura.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Help Footer -->
                    <tr>
                        <td style="background-color: rgba(249, 250, 251, 0.5); padding: 32px; text-align: center;">
                            <p style="font-size: 12px; color: #9ca3af; margin: 0 0 16px 0; line-height: 1.625;">
                                Dúvidas ou problemas? Nossa equipe de suporte está pronta para ajudar.<br/>
                                Acesse nossa <a href="{{ route('support') }}" style="color: #13ec92; font-weight: 700; text-decoration: none;">Central de Ajuda</a> ou responda a este e-mail.
                            </p>
                            
                            <!-- Social/Help Icon Stub -->
                            <div style="margin-bottom: 24px;">
                                <span style="color: #9ca3af; font-size: 20px;">?</span>
                            </div>

                            <p style="font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 500; margin: 0;">
                                © 2024 Everest Technologies Inc.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- System Footer -->
                <div style="max-width: 600px; margin: 24px auto 0 auto; text-align: center;">
                    <p style="font-size: 12px; color: #9ca3af; margin: 0;">
                        Enviado com ❤️ pela equipe Everest.
                    </p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
