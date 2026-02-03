<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Everest - Alerta de Ofensivas</title>
</head>
<body style="background-color: #f6f8f7; color: #111815; font-family: 'Manrope', Arial, Helvetica, sans-serif; margin: 0; padding: 0; min-height: 100vh; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f6f8f7; width: 100%;">
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <!-- Main Container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 32px; overflow: hidden; border: 1px solid #dbe6e1; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
                    
                    <!-- Header with Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #ff6b6b 0%, #ff9f1c 100%); padding: 48px 32px; text-align: center;">
                            <!-- Fire Icon -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 24px;">
                                        <div style="display: inline-block; width: 80px; height: 80px; background-color: rgba(255, 255, 255, 0.2); border-radius: 50%; line-height: 80px; text-align: center; border: 2px solid rgba(255, 255, 255, 0.3);">
                                            <span style="font-size: 40px;">🔥</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        @if($period === 'morning')
                                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 900; letter-spacing: -0.025em; line-height: 1.2; margin: 0; text-transform: uppercase;">
                                            Bom dia! Sua Ofensiva Espera
                                        </h1>
                                        @else
                                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 900; letter-spacing: -0.025em; line-height: 1.2; margin: 0; text-transform: uppercase;">
                                            @if(count($goals) > 1)
                                                {{ count($goals) }} Ofensivas em Risco!
                                            @else
                                                Sua Ofensiva está em Risco!
                                            @endif
                                        </h1>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 0 32px 40px 32px; margin-top: -20px;">
                            <!-- White Card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border-radius: 24px; margin-top: -20px; position: relative;">
                                <tr>
                                    <td style="padding: 32px; text-align: center;">
                                        <!-- Greeting -->
                                        <p style="font-size: 18px; color: #4b5563; margin: 0 0 24px 0; line-height: 1.6;">
                                            Olá, <strong style="color: #111815;">{{ $user->nickname ?? $user->name }}</strong>!
                                            @if($period === 'morning')
                                                Não esqueça de confirmar suas metas hoje.
                                            @else
                                                O dia está acabando e suas ofensivas precisam de você!
                                            @endif
                                        </p>

                                        <!-- Global Streak Card -->
                                        @if($globalStreak > 0)
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 24px;">
                                            <tr>
                                                <td style="background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(255, 107, 107, 0.05) 100%); border: 1px solid #f3f4f6; border-radius: 16px; padding: 20px; text-align: center;">
                                                    <span style="font-size: 32px; display: block; margin-bottom: 8px;">🔥</span>
                                                    <span style="font-size: 36px; font-weight: 900; color: #111815; display: block;">{{ $globalStreak }}</span>
                                                    <span style="font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em;">Ofensiva Global</span>
                                                </td>
                                            </tr>
                                        </table>
                                        @endif

                                        <!-- Goals at Risk -->
                                        @if(count($goals) > 0)
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 32px;">
                                            @foreach($goals as $goal)
                                            <tr>
                                                <td style="background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(255, 159, 28, 0.05) 100%); border: 1px solid #f3f4f6; border-radius: 16px; padding: 16px; margin-bottom: 12px;">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td width="50" style="vertical-align: middle;">
                                                                <span style="font-size: 28px;">🔥</span>
                                                            </td>
                                                            <td style="vertical-align: middle; text-align: left;">
                                                                <span style="font-weight: 700; color: #111815; display: block;">{{ $goal['title'] }}</span>
                                                                <span style="font-size: 12px; color: #6b7280;">{{ $goal['current_streak'] }} dias de ofensiva</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @if(!$loop->last)
                                            <tr><td style="height: 12px;"></td></tr>
                                            @endif
                                            @endforeach
                                        </table>
                                        @endif

                                        <!-- CTA Button -->
                                        <a href="{{ route('dashboard') }}" style="display: inline-block; width: 100%; box-sizing: border-box; background-color: #13ec92; color: #111815; font-size: 18px; font-weight: 900; text-align: center; text-decoration: none; padding: 18px 32px; border-radius: 9999px; box-shadow: 0 10px 15px -3px rgba(19, 236, 146, 0.3); border: none;">
                                            Salvar Ofensivas
                                        </a>

                                        <!-- Motivational Quote -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 32px; border-top: 1px solid #f3f4f6; padding-top: 24px;">
                                            <tr>
                                                <td style="text-align: center;">
                                                    <p style="font-size: 14px; color: #9ca3af; font-style: italic; margin: 0 0 16px 0;">
                                                        @if($period === 'morning')
                                                            "O segredo do sucesso é a constância do propósito."
                                                        @else
                                                            "A consistência é o que transforma o comum em extraordinário."
                                                        @endif
                                                    </p>
                                                    
                                                    <!-- Bonus Reminder -->
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td style="background-color: rgba(19, 236, 146, 0.1); border: 1px solid rgba(19, 236, 146, 0.2); border-radius: 16px; padding: 16px;">
                                                                <p style="color: #059669; font-weight: 700; font-size: 14px; margin: 0;">
                                                                    ⚡ Mantenha suas sequências para garantir seu <u>Bônus de 2x XP</u>!
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px; text-align: center; border-top: 1px solid #dbe6e1;">
                            <!-- Logo -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 16px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="vertical-align: middle; padding-right: 8px;">
                                                    <div style="width: 24px; height: 24px; color: #13ec92;">
                                                        <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                                                            <path clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <span style="font-size: 16px; font-weight: 700; color: #111815;">Everest</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="font-size: 11px; color: #9ca3af; line-height: 1.6; margin: 0;">
                                Você está recebendo este e-mail porque suas metas do dia ainda não foram registradas.<br>
                                <a href="{{ route('dashboard') }}" style="color: #9ca3af; text-decoration: underline;">Configurações</a> • 
                                <a href="#" style="color: #9ca3af; text-decoration: underline;">Cancelar inscrição</a>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <!-- System Footer -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; margin: 0 auto;">
                    <tr>
                        <td style="text-align: center; padding-top: 24px;">
                            <p style="font-size: 10px; color: #9ca3af; margin: 0;">© {{ date('Y') }} Everest. Todos os direitos reservados.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
