<!DOCTYPE html>
<html lang="<?= SITE_LANG ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-IFeitório | 404</title>
    <link rel="icon" href="/projetotcc/storage/images/logoBarra.png" />

    <style>
        @font-face {
            src: url(/projetotcc/storage/fonts/ModesticSans.ttf);
            font-family: Modestic-Sans;
        }

        body {
            background-color: #FF881C;
            font-family: Modestic-Sans;

            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        .errorPage {
            text-align: center;
        }

        .error-code {
            font-size: 100px;
            color: #ffffff;
            margin: 0;
        }

        .error-message {
            font-size: 24px;
            color: #ffffff;
        }

        .suggestion {
            font-size: 18px;
            color: #ffffff;
        }

        .suggestion a {
            color: #ffffff;
        }

        .whiteLogo {
            margin: 0 100px;
        }
    </style>
</head> 

<body>
    <img class="whiteLogo" src="/projetotcc/storage/images/cryingEmoji.png" alt="Logo" height="173">

    <div class="errorPage">
        <h1 class="error-code">404</h1>
        <p class="error-message">Ops! Página não encontrada.</p>
        <p class="suggestion">Tente revisar o URL, ou volte para a <a href="/projetotcc/">página principal.</a></p>
    </div>
</body>