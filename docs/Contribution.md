## Beitrag zum Projekt – Bugfixes & Features
Letzte Änderungen: 20.04.2025
[Change to the english version](Contribution_en.md)

Vielen Dank, dass du zu unserem Projekt beitragen möchtest!  
Damit wir unsere Releases konsistent und stabil halten können, nutzen wir eine strukturierte Branch-Strategie. Bitte folge den nachstehenden Anweisungen, je nachdem, ob du einen **Bugfix** oder ein **neues Feature** beisteuern möchtest.

---

### Bugfix beitragen

**Ziel:** Bugfixes gehen immer in den `master`-Branch.  
Der `master`-Branch wird regelmäßig für Bugfix-Releases verwendet (z. B. `4.0.1`, `4.0.2`).

#### Schritte:

1. Forke das Repository (falls noch nicht geschehen).
2. Erstelle einen neuen Branch von `master`:
   ```bash
   git checkout master
   git pull origin master
   git checkout -b fix/<kurze-beschreibung>
   ```
3. Implementiere den Bugfix.
4. Schreibe einen passenden Commit-Message (z. B. `Fix: fixes PHP error during upload`).
5. Öffne einen Pull Request **gegen den `master`-Branch**.
6. Optional: Wenn du weißt, dass der Bug auch in einem zukünftigen Feature-Branch (z. B. `4.1.0`) relevant ist, weise im PR darauf hin.

---

### Feature beitragen

**Ziel:** Neue Features landen **nicht in `master`**, sondern in einem separaten Branch für das nächste Minor-Release (z. B. `4.1.0`).

#### Schritte:

1. Forke das Repository (falls noch nicht geschehen).
2. Erstelle einen neuen Branch von `4.1.0`:
   ```bash
   git checkout 4.1.0
   git pull origin 4.1.0
   git checkout -b feat/<kurze-beschreibung>
   ```
3. Implementiere dein Feature.
4. Schreibe einen passenden Commit-Message (z. B. `Feat: fügt Dark Mode hinzu`).
5. Öffne einen Pull Request **gegen den `4.1.0`-Branch**.

---

### Branch-Namenskonventionen

Bitte benenne deine Branches nach diesem Schema:

| Typ       | Prefix       | Beispiel                    | Beschreibung |
|-----------|--------------|-----------------------------|--------------|
| **Bugfix**   | `fix/`       | `fix/login-error`           | Fehlerbehebungen|
| **Feature**  | `feat/`      | `feat/user-profile-page`    | Neue Funktionen |
| **Refactor** | `refactor/`  | `refactor/form-validation`  | Code-Umstrukturierungen ohne funktionale Änderung |
| **Chore**    | `chore/`     | `chore/update-dependencies` | Wartungsarbeiten wie Abhängigkeiten, Linting, Build-Tools etc. |

Diese Konvention hilft uns, Branches schnell einzuordnen.

---

### Hinweise zum Branch-Modell

- `master`: Nur für Bugfixes (Bugfix-Releases)
- `4.1.0`, `4.2.0`, ...: Feature-Entwicklung für nächste Minor-Versionen
- Bugfixes werden selektiv vom `master` in die Feature-Branches übernommen – du musst dich nicht darum kümmern.
- Bitte **merke dir, gegen welchen Branch du deinen PR öffnest** – PRs gegen den falschen Branch verzögern das Review.

---

### Checkliste vor dem PR

- [ ] Teste deinen code lokal
- [ ] Passende Branchwahl (`master` für Bugfixes, `4.1.0` für Features)
- [ ] Beschreibung im PR ausgefüllt
- [ ] Füge entsprechende Tags zum PR hinzu (z.B `needs testing`)

---

Danke fürs Mitmachen
Wenn du Fragen hast, melde dich gerne per GitHub Issue oder Kommentar im Pull Request!
