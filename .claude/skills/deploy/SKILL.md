---
name: deploy
description: Комітить зміни і пушить в master — запускає автодеплой на bajka.pp.ua через GitHub Actions. Use when: задеплой, deploy, запушити, опублікувати зміни, push.
allowed-tools: bash
---

# Skill: deploy

## Призначення
Стейджить всі зміни, робить коміт і пушить в `master`.
GitHub Actions автоматично деплоїть на `bajka.pp.ua/minecraft/lua/` (~25 сек).

## Крок 1: Перевір стан

```bash
git -C d:/CCTweak_LuaPageCoding status
git -C d:/CCTweak_LuaPageCoding diff --stat
```

Якщо змін немає — повідом користувача і зупинись.

## Крок 2: Сформуй commit message

На основі змін (git diff) сформуй короткий опис українською або англійською.
Формат: `тип: що зроблено` (наприклад: `feat: redstone API`, `fix: tab switch bug`, `ci: update deploy`).

Якщо користувач передав повідомлення як аргумент — використай його.

## Крок 3: Коміт і push

```bash
cd d:/CCTweak_LuaPageCoding
git add .
git commit -m "повідомлення коміту"
git push
```

## Крок 4: Повідом результат

- Назви хеш коміту
- Нагадай що деплой займе ~25 секунд
- Посилання для перевірки Actions: https://github.com/asemelinsky/CCTweak_LuaPageCoding/actions
