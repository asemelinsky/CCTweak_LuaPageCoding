# CCTweak Lua Editor — CLAUDE.md

## Що це
Веб-редактор Lua-коду для ComputerCraft (Minecraft мод).
Пишеш код → тестуєш у браузері → надсилаєш на Minecraft-сервер.

## Хостинг і деплой
- **Сайт:** bajka.pp.ua/minecraft/lua/
- **FTP:** soyldohv@b5.s-host.com.ua (порт 21)
- **Деплой:** автоматично при `git push master` → GitHub Actions (~25 сек)
- **GitHub:** https://github.com/asemelinsky/CCTweak_LuaPageCoding

## Дві різні SFTP/FTP точки (не плутати!)
| Призначення | Протокол | Хост | Що там |
|---|---|---|---|
| Веб-файли (деплой) | FTP | b5.s-host.com.ua:21 | index.html, upload.php, list_files.php |
| Minecraft дані | SFTP | wing79.panel.godlike.host:2022 | world/computercraft/computer/{N}/*.lua |

## Структура проєкту
```
CCTweak_LuaPageCoding/
├── index.html              ← головний редактор (Ace Editor + симулятор)
├── upload.php              ← SFTP-завантаження .lua на Minecraft-сервер
├── list_files.php          ← перегляд файлів на сервері
├── turtle_completions.js   ← застарілий completers (completions вже в index.html)
├── index_v1–v5.html        ← архів старих версій
├── docs/                   ← звіти про роботу (YYYY-MM-DD.md)
├── .github/workflows/
│   └── deploy.yml          ← GitHub Actions FTP деплой
└── .claude/
    └── skills/
        ├── daily-report/   ← /daily-report — звіт в docs/
        └── deploy/         ← /deploy — коміт + push + деплой
```

## Технічний стек index.html
- **Ace Editor** CDN — підсвітка Lua, автодоповнення
- **Симулятор** — Lua → JS транспіляція для тестування в браузері
- **Моки:** turtle API (повний), rs/redstone API, os, io, print/read
- **Таби** — кілька файлів, зберігаються в localStorage

## Правила
- Не комітити паролі та credentials у файли
- Деплой тільки через git push (не вручну по FTP)
- Звіти зберігати в docs/ через skill /daily-report
