# CCTweak Lua Page Coding

Веб-редактор Lua-коду для **ComputerCraft** (Minecraft мод).
Дозволяє писати, тестувати та надсилати Lua-скрипти прямо на ігровий сервер через SFTP.

## Можливості

- **Ace Editor** з підсвіткою синтаксису Lua та автодоповненням
- **Симулятор** — тестує Lua-код у браузері (Lua → JS транспіляція)
- **turtle API** — повний мок черепашки ComputerCraft (рух, копання, інвентар, паливо, детекція)
- **Redstone API** — мок `rs.*` / `redstone.*` команд з логом у консоль
- **Вкладки** — кілька файлів одночасно, зберігаються в localStorage
- **Upload на сервер** — SFTP-завантаження в `world/computercraft/computer/{N}/`
- **Файловий менеджер** — перегляд та завантаження файлів з ігрового сервера
- Масштабування шрифту редактора

## Структура

```
CCTweak_LuaPageCoding/
├── index.html              ← головний редактор (Ace Editor + симулятор)
├── upload.php              ← PHP: SFTP-завантаження файлу на Minecraft-сервер
├── list_files.php          ← PHP: перегляд та читання файлів на сервері
├── turtle_completions.js   ← (застарілий) completers turtle API для Ace
├── index_v1.html           ← архів версій редактора
├── index_v2.html
├── index_v3.html
├── index_v4_20251230.html
└── index_v5_20260118.html
```

## Технічний стек

- **Frontend:** Vanilla HTML/CSS/JS — без фреймворків
- **Ace Editor:** CDN `cdnjs.cloudflare.com/ajax/libs/ace/1.32.3`
- **Backend:** PHP + [phpseclib3](https://phpseclib.com/) для SFTP
- **Хостинг:** `bajka.pp.ua` (godlike.host, PHP-сервер)

## Серверний шлях файлів

```
world/computercraft/computer/{номер_компʼютера}/{назва_файлу}.lua
```

## Upload API

`POST upload.php` з JSON:
```json
{ "code": "...", "comp": "278", "fileName": "program.lua" }
```

SFTP-реквізити читаються з ENV (`SFTP_HOST`, `SFTP_PORT`, `SFTP_USER`, `SFTP_PASS`),
fallback — хардкод у файлі (**не рекомендується для production**).

## Локальний запуск

Відкрий `index.html` у браузері.
Upload та файловий менеджер потребують PHP-сервера (Apache/nginx з PHP).

## ComputerCraft API у симуляторі

| Namespace | Методи |
|---|---|
| `turtle` | forward, back, up, down, turnLeft, turnRight, dig\*, place\*, select, detect\*, compare\*, attack\*, drop\*, suck\*, refuel, getFuelLevel, inspect\*, getItemDetail, equipLeft/Right, transferTo |
| `rs` / `redstone` | setOutput, getOutput, getInput, setAnalogOutput, getAnalogOutput, getAnalogInput |
| `os` | sleep |
| `io` | read, write |
| базові | print, read, write, sleep, tonumber, tostring |
