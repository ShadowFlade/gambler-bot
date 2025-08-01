<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Release Notes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
            padding: 2rem;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .content {
            padding: 2rem;
        }

        .release {
            margin-bottom: 3rem;
            border-left: 4px solid #e2e8f0;
            padding-left: 1.5rem;
        }

        .release:last-child {
            margin-bottom: 0;
        }

        .release-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .version {
            background: #667eea;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .date {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status.released {
            background: #dcfce7;
            color: #166534;
        }

        .status.upcoming {
            background: #fef3c7;
            color: #92400e;
        }

        .status.in-progress {
            background: #dbeafe;
            color: #1e40af;
        }

        .release-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .release-description {
            color: #64748b;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .features {
            list-style: none;
        }

        .features li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.75rem;
            color: #374151;
        }

        .features li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #667eea;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .feature-type {
            display: inline-block;
            padding: 0.125rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-right: 0.5rem;
        }

        .feature-type.new {
            background: #dcfce7;
            color: #166534;
        }

        .feature-type.improvement {
            background: #dbeafe;
            color: #1e40af;
        }

        .feature-type.fix {
            background: #fef2f2;
            color: #dc2626;
        }

        .feature-type.breaking {
            background: #fef3c7;
            color: #92400e;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .content {
                padding: 1.5rem;
            }

            .release-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <header class="header">
        <h1>Релизные заметки</h1>
    </header>

    <main class="content">
        <!-- Version 0.1 -->
        <section class="release">
            <div class="release-header">
                <span class="version">v0.1</span>
                <span class="date">06.07.2025</span>
                <span class="status.released">Опубликован</span>
            </div>
            <h2 class="release-title">Основной функционал (первый релиз)</h2>
            <p class="release-description">
                Minor release
            </p>
            <ul class="features">
                <li>
                    <span class="feature-type new">Регистрация пользователей</span>
                </li>
                <li>
                    <span class="feature-type new">Статистика по количеству выигрышей (+ процентное соотношение)</span>
                </li>
                <li>
                    <span class="feature-type new">Статистика по количеству выигранных денег</span>
                </li>
            </ul>
        </section>

        <section class="release">
            <div class="release-header">
                <span class="version">v0.1.1</span>
                <span class="date">07.07.2025</span>
                <span class="status.released">Опубликован</span>
            </div>
            <h2 class="release-title">Патч первого дня по запросам пользователей</h2>
            <p class="release-description">
                PATCH
            </p>
            <ul class="features">
                <li>
                    <span class="feature-type new">Исправлен вывод статистики (команда <code>/statistics</code>)</span>
                    <p>Более компактный вид + добавлен баланс</p>
                    <div>
                        Было:
                        <div>
                            <img src="{{asset('/img/1.jpg')}}" alt="">
                        </div>
                    </div>
                    <div>
                        Стало:
                        <div>
                            <img src="{{asset('/img/2.jpg')}}" alt="">
                        </div>
                    </div>

                </li>
            </ul>
        </section>

        <section class="release">
            <div class="release-header">
                <span class="version">v0.2</span>
                <span class="date">13.07.2025</span>
                <span class="status.released">Опубликован</span>
            </div>
            <h2 class="release-title">Дополнения функционала статистики</h2>
            <p class="release-description">
                Minor release
            </p>
            <ul class="features">
                <li>
                    <span class="feature-type new">Персональная статистика</span>
                </li>
                <li>
                    <span class="feature-type new">Статистика по типам выигранных слотов</span>
                </li>
            </ul>
        </section>

        <section class="release">
            <div class="release-header">
                <span class="version">v0.3</span>
                <span class="date">01.08.2025</span>
                <span class="status.released">Опубликован</span>
            </div>
            <h2 class="release-title">Quality of life improvements</h2>
            <p class="release-description">
                Minor release
            </p>
            <ul class="features">
                <li>
                    <span class="feature-type new">Улучшено форматирование</span>
                    <div>
                        <img src="{{asset('/img/3.jpg')}}" alt="">
                    </div>
                </li>
                <li>
                    <span class="feature-type new">Цены вынесены в БД</span>
                </li>
                <li>
                    <span class="feature-type new">Добавлена команда <code>/info</code></span>
                </li>
            </ul>
        </section>
    </main>
</div>
</body>
</html>