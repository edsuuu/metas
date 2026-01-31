<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Everest - Chamado Respondido</title>
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
            <td align="center" style="padding: 16px;">
                <!-- Main Container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; border: 1px solid #dbe6e1; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 40px 32px 24px 32px; border-bottom: 1px solid #f9fafb;">
                            <div style="margin-bottom: 16px;">
                                <div style="display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                    <div style="width: 40px; height: 40px; color: #13ec92;">
                                        <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" width="40" height="40">
                                            <path clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V44Z" fill-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <h1 style="color: #111815; font-size: 24px; font-weight: 800; margin: 0;">Everest</h1>
                                </div>
                            </div>
                            <div style="display: inline-block; padding: 6px 16px; border-radius: 9999px; background-color: rgba(19, 236, 146, 0.1); color: #047857; font-size: 14px; font-weight: 700;">
                                Suporte Técnico
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px 48px;">
                            <h2 style="font-size: 24px; font-weight: 900; color: #111815; margin: 0 0 24px 0; line-height: 1.2;">
                                Seu chamado <span style="color: #13ec92;">#{{ $ticket->id }}</span> foi respondido!
                            </h2>
                            <p style="color: #4b5563; font-size: 18px; margin: 0 0 32px 0; line-height: 1.6;">
                                Olá, temos uma nova atualização sobre a sua solicitação. Nossa equipe de suporte acaba de enviar uma resposta detalhada para o seu caso.
                            </p>

                            <!-- Summary Box -->
                            <div style="background-color: #f6f8f7; border-radius: 16px; padding: 24px; margin-bottom: 40px; border-left: 4px solid #13ec92;">
                                <h3 style="font-size: 12px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 12px 0;">Resumo da Atualização</h3>
                                <p style="color: #374151; font-style: italic; margin: 0;">
                                    "{{ Str::limit($reply->message ?? 'Nova resposta disponível.', 100) }}"
                                </p>
                            </div>

                            <!-- CTA -->
                            <div style="text-align: center;">
                                <a href="{{ route('support.ticket.show', $ticket->id) }}" style="display: inline-block; width: 100%; box-sizing: border-box; background-color: #13ec92; color: #111815; font-size: 16px; font-weight: 800; text-align: center; text-decoration: none; padding: 20px 40px; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(19, 236, 146, 0.2); margin-bottom: 24px;">
                                    Visualizar Resposta Completa
                                </a>
                                <p style="font-size: 14px; color: #9ca3af; margin: 0;">
                                    Você também pode acompanhar todos os seus chamados na <a href="{{ route('support') }}" style="color: #13ec92; font-weight: 700; text-decoration: none;">Central de Ajuda</a>.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 32px; text-align: center; border-top: 1px solid #dbe6e1;">
                            <p style="font-size: 14px; color: #6b7280; margin: 0 0 24px 0;">
                                Este é um e-mail automático enviado pelo Everest. Por favor, não responda a esta mensagem diretamente.
                            </p>
                            
                            <div style="margin-bottom: 32px;">
                                <a href="{{ route('support') }}" style="color: #9ca3af; text-decoration: none; margin: 0 12px; font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">Base de Conhecimento</a>
                                <a href="{{ route('terms.intro') }}" style="color: #9ca3af; text-decoration: none; margin: 0 12px; font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">Privacidade</a>
                                <a href="{{ route('terms.intro') }}" style="color: #9ca3af; text-decoration: none; margin: 0 12px; font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">Termos de Uso</a>
                            </div>

                            <div style="border-top: 1px solid #e5e7eb; padding-top: 24px;">
                                <p style="font-size: 10px; color: #9ca3af; line-height: 1.6; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">
                                    © 2024 Everest Technologies Inc. <br/>
                                    Everest HQ - Av. Paulista, 1000, São Paulo, SP
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
