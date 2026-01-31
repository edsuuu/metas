<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Bem-vindo ao Everest</title>
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
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f6f8f7; width: 100%;">
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <!-- Main Container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 32px; overflow: hidden; border: 1px solid #dbe6e1; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #10221a 0%, #0a1510 100%); padding: 40px; text-align: center;">
                            <!-- Logo Area -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 32px;">
                                        <div style="display: inline-flex; align-items: center; justify-content: center;">
                                            <div style="width: 32px; height: 32px; color: #13ec92; display: inline-block; vertical-align: middle; margin-right: 8px;">
                                                <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" width="32" height="32">
                                                    <path clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V44Z" fill-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span style="color: #ffffff; font-size: 20px; font-weight: 700; letter-spacing: -0.025em; display: inline-block; vertical-align: middle;">Everest</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Image Area -->
                            <div style="margin-bottom: 24px; position: relative; border-radius: 16px; overflow: hidden;">
                                <img alt="Mountain Peak" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA2RKla8NYAm1hS6TLP9ZqQbanUV_ELvx3SQosCNrhOsItKcDBTk-d2d8O0ODDgm17ZnhLR6udacHbQmK66IzoCH-3qSxuJAH_W-ksFzxbeS-T4NKREz1oEK2ZM5Kxdd-6m5Dsqom-ZRUvnISZMCcqvtXPCEBUYeHVCWbu0rHcoKcU-E5cnDjzOR10DRLl-gbZH_w4AgBg0vEtPW5jOYrsOoIb-iFlgA63AOpq5J8JR75fr81bkXkF0D0RQ9XjxRLeLhW3cjsjJLZ8" style="width: 100%; height: 192px; object-fit: cover; border-radius: 16px; opacity: 0.8; display: block;" />
                            </div>

                            <!-- Title -->
                            <h1 style="color: #ffffff; font-size: 30px; font-weight: 900; letter-spacing: -0.025em; line-height: 1.2; margin: 0;">
                                Bem-vindo ao topo, <span style="color: #13ec92;">{{ $user->nickname ?? $user->name }}</span>!
                            </h1>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 32px 48px; text-align: center;">
                            <p style="font-size: 18px; color: #4b5563; margin-bottom: 32px; line-height: 1.6;">
                                A jornada para suas maiores conquistas começa hoje. Estamos empolgados em ter você conosco nesta escalada rumo às suas metas pessoais!
                            </p>

                            <!-- Special Offer Box -->
                            <div style="background-color: rgba(19, 236, 146, 0.1); border: 1px solid rgba(19, 236, 146, 0.2); border-radius: 16px; padding: 16px; margin-bottom: 40px;">
                                <p style="color: #13ec92; font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 4px 0;">Oferta Especial</p>
                                <p style="color: #111815; font-weight: 700; margin: 0;">Você acaba de ganhar 14 dias grátis de acesso total!</p>
                            </div>

                            <!-- Steps Section -->
                            <div style="text-align: left; margin-bottom: 40px;">
                                <h3 style="font-size: 14px; font-weight: 900; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 16px;">Primeiros passos rápidos:</h3>
                                
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td valign="top" style="width: 40px; padding-bottom: 24px;">
                                            <div style="width: 40px; height: 40px; background-color: #f6f8f7; border: 1px solid #dbe6e1; border-radius: 50%; text-align: center; line-height: 40px;">
                                                <span style="color: #13ec92; font-weight: 700;">1</span>
                                            </div>
                                        </td>
                                        <td style="padding-left: 16px; padding-bottom: 24px;">
                                            <h4 style="font-weight: 700; color: #111815; margin: 0 0 4px 0;">Crie sua primeira meta</h4>
                                            <p style="font-size: 14px; color: #6b7280; margin: 0;">Defina o que você quer alcançar e dê o primeiro passo.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" style="width: 40px; padding-bottom: 24px;">
                                            <div style="width: 40px; height: 40px; background-color: #f6f8f7; border: 1px solid #dbe6e1; border-radius: 50%; text-align: center; line-height: 40px;">
                                                <span style="color: #13ec92; font-weight: 700;">2</span>
                                            </div>
                                        </td>
                                        <td style="padding-left: 16px; padding-bottom: 24px;">
                                            <h4 style="font-weight: 700; color: #111815; margin: 0 0 4px 0;">Ative sua ofensiva</h4>
                                            <p style="font-size: 14px; color: #6b7280; margin: 0;">Mantenha a constância e não deixe a chama apagar.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" style="width: 40px;">
                                            <div style="width: 40px; height: 40px; background-color: #f6f8f7; border: 1px solid #dbe6e1; border-radius: 50%; text-align: center; line-height: 40px;">
                                                <span style="color: #13ec92; font-weight: 700;">3</span>
                                            </div>
                                        </td>
                                        <td style="padding-left: 16px;">
                                            <h4 style="font-weight: 700; color: #111815; margin: 0 0 4px 0;">Veja o ranking</h4>
                                            <p style="font-size: 14px; color: #6b7280; margin: 0;">Acompanhe seu progresso em relação à comunidade.</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- CTA Button -->
                            <a href="{{ route('dashboard') }}" style="display: inline-block; width: 100%; box-sizing: border-box; background-color: #13ec92; color: #111815; font-size: 18px; font-weight: 900; text-align: center; text-decoration: none; padding: 16px 32px; border-radius: 9999px; box-shadow: 0 10px 15px -3px rgba(19, 236, 146, 0.2); margin-bottom: 32px; border: none;">
                                Começar agora
                            </a>

                            <!-- Footer Links -->
                            <div style="border-top: 1px solid #dbe6e1; padding-top: 32px;">
                                <p style="font-size: 12px; color: #9ca3af; margin: 0 0 8px 0;">Se você tiver alguma dúvida, nossa equipe de guias está pronta para ajudar.</p>
                                <a href="{{ route('support') }}" style="font-size: 12px; font-weight: 700; color: #13ec92; text-decoration: none;">Visitar Central de Ajuda</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px; text-align: center; border-top: 1px solid #dbe6e1;">
                            <p style="font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 16px 0; font-weight: 700;">Everest Technologies Inc.</p>
                            
                            <p style="font-size: 10px; color: #9ca3af; line-height: 1.6; margin: 0;">
                                Você recebeu este e-mail porque se inscreveu no Everest.<br>
                                <a href="#" style="color: #9ca3af; text-decoration: underline;">Descadastrar-se</a> • <a href="#" style="color: #9ca3af; text-decoration: underline;">Preferências de e-mail</a>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <!-- System Footer -->
                <div style="max-width: 600px; margin: 0 auto; text-align: center; padding-top: 24px;">
                    <p style="font-size: 10px; color: #9ca3af; margin: 0;">© 2024 Everest. Todos os direitos reservados.</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
