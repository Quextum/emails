Emails
=============

Konfigurace
-------
V konfiguračním souboru [config.neon](/app/config/config.neon) registrujte rozšíření:
```neon
    extensions:
        emails: App\Emails\EmailsExtension
```
Poté rozšíření nastavte. Ukázková konfigurace:
```neon
    parameters:
        domain: domain.com
        title: Title

    emails:
        templates: '%appDir%/emails/templates'
        default:
            from: [noreply@%domain%, %title%]
        password < default:
            subject: Nastavení hesla na stránkách %domain%
            variables:
                imagesUrl: https://cdn.domain.com/xyz/
        registration < default:
            subject: Byl jste přidán do systému %domain%
        notification < default:
            subject: Notifikace ze systému %domain%
        contact < default:
            subject: Zpráva z kontaktního formuláře serveru %domain%
            attachment:
                - %appDir%/emails/assets/about_blank.pdf
            embed:
                - %appDir%/emails/assets/prosky-logo.png
            to:
                test@email.cz: Jan Novák
                - test2@email.com
                - "Jan Novák <test3@email.com>"
```

Nastavit je možné:

        to
        from
        subject
        cc
        bcc
        reply
        return
        embed
        attachment

Použití:
-------
V presenteru:

    $this->context->getService('emails')->send('registration', ['to' => $user->email],['userEntity'=> $user]);

Parametry:
1. Typ. Je nutné aby pro specifikovaný typ existovala konfigurace a šablona se schodným názvem.
2. Nastavení. Toto nastavení se spojí s definovaným nastavením v konfiguraci. Nastavení v parametru metody má větší váhu něž konfigurační soubor.
3. Proměnné šablony.
