# Upmerge: Änderungen von `master` nach `4.x.0` übernehmen
Letzte Änderungen: 10.06.2025
[Change to the english version](Upmerge_en.md)

Dieses Dokument beschreibt, wie ein Contributor oder Maintainer einen **Upmerge von `master` in einen MINOR-Release Branch `v4.x.0`** durchführt – über einen Pull Request aus dem eigenen Fork (origin) in das zentrale Repository (upstream).

> Hinweis: Dieses Vorgehen verwendet **kein zusätzliches temporäres Merge-Branching**, sondern basiert direkt auf dem MINOR-Release Branch.

Für diese Anleitung wird beispielhaft der MINOR-Release Branch `v4.1.0` verwendet.

---

## Ziel

- Änderungen (z. B. Bugfixes) aus `master` sollen auch im MINOR-Release Branch verfügbar sein.
- Der Merge erfolgt über einen PR von `origin/v4.1.0` nach `upstream/v4.1.0`.
- Keine direkten Pushes auf `upstream`.

---

## Schritte

### 1. Branch `v4.1.0` lokal erstellen falls nicht vorhanden (basiert auf `upstream/v4.1.0`)
```bash
git fetch upstream
git checkout -b v4.1.0 upstream/v4.1.0
```

### 2. Branch in den eigenen Fork (origin) pushen
```bash
git push origin v4.1.0
```

### 3. Upmerge von `upstream/master` in den lokalen Branch
```bash
git merge upstream/master
```

### 4. Konflikte lösen (falls nötig) und änderungen commiten
```bash
git add *
git commit -m "upmerge <YYYY-MM-DD>"
```

### 5. Merge-Änderungen in den Fork pushen
```bash
git push origin v4.1.0
```

---

## 6. Pull Request erstellen

Auf GitHub öffnest du einen neuen Pull Request:

- **Base:** `upstream/v4.1.0`
- **Compare:** `origin/v4.1.0`
- **Titel:** `[Chore] Upmerge master into v4.1.0`
- **Beschreibung:** Optional – z. B. "Merged latest bugfixes from master into v4.1.0"

---

## Hinweise

- Upmerges regelmäßig durchführen, z. B. nach jedem relevanten `master`-Bugfix.
- Vor Merge sicherstellen, dass beide branches aktualisiert sind:
  ```bash
  git pull upstream/v4.1.0
  git pull upstream/master
  ```

---

## Vorteile

- ✅ Kein temporärer Merge-Branch notwendig
- ✅ Sicherer Merge über PR statt Direkt-Push
- ✅ Volle CI-Unterstützung & Review-Prozess
- ✅ Reproduzierbar & teamfreundlich
