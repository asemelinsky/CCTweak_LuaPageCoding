# CCTweak Lua Editor — CLAUDE.md

## Що це
Веб-редактор Lua-коду для ComputerCraft (Minecraft мод).
Пишеш код → тестуєш у браузері → надсилаєш на Minecraft-сервер.

## Хостинг і деплой
- **Сайт:** Vercel (проєкт: cctweaked-lua-editor)
- **Деплой:** автоматично при `git push master` → Vercel
- **GitHub:** https://github.com/asemelinsky/CCTweak_LuaPageCoding
- **Стара сторінка:** bajka.pp.ua/minecraft/lua/ — НЕ використовувати, НЕ деплоїти туди

## Minecraft SFTP сервери
| Призначення | Хост | Порт | Юзер | Шлях |
|---|---|---|---|---|
| Server 1 (Forge 1.20.1) | 46.225.227.42 | 2022 | admin.3c4202c1 | world/computercraft/computer/{N}/*.lua |
| Server 2 (NeoForge 1.21.1) | 46.225.227.42 | 2022 | admin.cfc9be31 | world/computercraft/computer/{N}/*.lua |

## Структура проєкту
```
CCTweak_LuaPageCoding/
├── index.html              ← головний редактор (Ace Editor + симулятор)
├── upload.php              ← (застаріло, для bajka) SFTP-завантаження
├── list_files.php          ← (застаріло, для bajka) перегляд файлів
├── api/upload.js           ← Vercel serverless — SFTP-завантаження .lua
├── api/list_files.js       ← Vercel serverless — перегляд/завантаження/видалення файлів
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
- Деплой тільки через git push на Vercel (НЕ на bajka.pp.ua — це стара сторінка)
- Звіти зберігати в docs/ через skill /daily-report
