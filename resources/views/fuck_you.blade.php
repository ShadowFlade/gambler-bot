<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Страница не найдена | Упс!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            overflow: hidden;
        }

        .container {
            text-align: center;
            max-width: 700px;
            padding: 2rem;
            position: relative;
            z-index: 2;
        }

        .error-code {
            font-size: 8rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            animation: bounce 2s infinite;
        }

        .error-message {
            font-size: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .funny-text {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .bear {
            font-size: 4rem;
            margin: 2rem 0;
            animation: float 3s ease-in-out infinite;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .floating-element {
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: float-around 15s linear infinite;
        }

        .jokes {
            margin: 2rem 0;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .joke {
            font-style: italic;
            margin: 0.5rem 0;
            opacity: 0.8;
            font-size: 1rem;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }

        @keyframes float-around {
            0% {
                transform: translateX(-100px) translateY(100vh) rotate(0deg);
            }
            100% {
                transform: translateX(100vw) translateY(-100px) rotate(360deg);
            }
        }

        .search-box {
            margin: 2rem 0;
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .search-input {
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            min-width: 250px;
            outline: none;
        }

        .search-btn {
            padding: 0.75rem 1.5rem;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: #ff5252;
            transform: scale(1.05);
        }

        .russian-elements {
            margin: 1rem 0;
            font-size: 1.5rem;
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }

            .error-message {
                font-size: 1.5rem;
            }

            .funny-text {
                font-size: 1rem;
            }

            .search-input {
                min-width: 200px;
            }

            .buttons {
                flex-direction: column;
                align-items: center;
            }

            .container {
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
<div class="floating-elements">
    <div class="floating-element" style="left: 10%; animation-delay: 0s;">🐻</div>
    <div class="floating-element" style="left: 20%; animation-delay: 2s;">🪆</div>
    <div class="floating-element" style="left: 30%; animation-delay: 4s;">🥟</div>
    <div class="floating-element" style="left: 40%; animation-delay: 6s;">🎭</div>
    <div class="floating-element" style="left: 50%; animation-delay: 8s;">⭐</div>
    <div class="floating-element" style="left: 60%; animation-delay: 10s;">🚀</div>
    <div class="floating-element" style="left: 70%; animation-delay: 12s;">🐻</div>
    <div class="floating-element" style="left: 80%; animation-delay: 14s;">🪆</div>
</div>

<div class="container">
    <div class="error-code">404</div>
    <h1 class="error-message">Ой, что-то пошло не так!</h1>

    <div class="bear">🐻</div>

    <p class="funny-text">
        Похоже, эта страница решила уехать на дачу и забыла оставить адрес.
        Наши программисты уже отправили за ней медведя на велосипеде! 🚴‍♂️
    </p>

    <div class="russian-elements">
        🪆 🥟 🎭 ☭ 🐻 🪆
    </div>

    <div class="jokes">
        <div class="joke">"Почему сайт сломался? Потому что программист забыл покормить сервер борщом!" 🍲</div>
        <div class="joke">"404 ошибка - это как очередь в магазине: все знают что это такое, но никто не хочет в ней стоять." 🛒</div>
    </div>

{{--    <div class="search-box">--}}
{{--        <input type="text" class="search-input" placeholder="Поищем что-нибудь другое..." id="searchInput">--}}
{{--        <button class="search-btn" onclick="performSearch()">🔍 Искать</button>--}}
{{--    </div>--}}

    <div class="buttons">
        <a href="{{route('home')}}" class="btn btn-primary">
            🏠 На главную
        </a>
        <a href="javascript:history.back()" class="btn btn-secondary">
            ⬅️ Назад
        </a>
    </div>

    <p style="margin-top: 2rem; opacity: 0.7; font-size: 0.9rem;">
        Не переживайте, даже Роскосмос иногда теряет спутники! 🛰️
    </p>
</div>

<script>
    function performSearch() {
        const searchTerm = document.getElementById('searchInput').value;
        if (searchTerm.trim()) {
            // Можете настроить это для перенаправления на вашу страницу поиска
            window.location.href = `/search?q=${encodeURIComponent(searchTerm)}`;
        } else {
            alert('Пожалуйста, введите что-нибудь для поиска! 🔍');
        }
    }

    // Позволяет использовать Enter для поиска
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Русские шутки, которые меняются каждые несколько секунд
    const jokes = [
        "Почему сайт сломался? Потому что программист забыл покормить сервер борщом! 🍲",
        "404 ошибка - это как очередь в магазине: все знают что это такое, но никто не хочет в ней стоять. 🛒",
        "Эта страница ушла за хлебом и до сих пор не вернулась. 🍞",
        "Страница играет в прятки... и она мастер своего дела! 🙈",
        "Ошибка 404: Страница не найдена. Ошибка 405: Чувство юмора на месте! 😂",
        "Наша страница уехала на дачу копать картошку. 🥔",
        "Эта страница застряла в пробке на МКАД. 🚗",
        "Страница ушла в отпуск в Сочи и забыла вернуться. 🏖️"
    ];

    let currentJokeIndex = 0;
    const jokeElements = document.querySelectorAll('.joke');

    setInterval(() => {
        jokeElements.forEach((element, index) => {
            element.textContent = jokes[(currentJokeIndex + index) % jokes.length];
        });
        currentJokeIndex = (currentJokeIndex + 1) % jokes.length;
    }, 5000);
</script>
</body>
</html>