# Upmerge: Integrating Changes from `master` into `v4.x.0`  
Last updated: 10.06.2025  
[Switch to the German version](Upmerge.md)

This document describes how a contributor or maintainer performs an **upmerge from `master` into a MINOR release branch `v4.x.0`** – via a pull request from their own fork (`origin`) into the main repository (`upstream`).

> Note: This approach **does not require a separate temporary merge branch** – it works directly on the MINOR release branch.

This guide uses the MINOR release branch `v4.1.0` as an example.

---

## Objective

- Changes (e.g. bug fixes) from `master` should also be available in the MINOR release branch.
- The merge is done via a pull request from `origin/v4.1.0` into `upstream/v4.1.0`.
- No direct pushes to `upstream`.

---

## Steps

### 1. Create the `v4.1.0` branch locally if it does not exist (based on `upstream/v4.1.0`)
```bash
git fetch upstream
git checkout -b v4.1.0 upstream/v4.1.0
```

### 2. Push the branch to your own fork (`origin`)
```bash
git push origin v4.1.0
```

### 3. Merge changes from `upstream/master` into your local branch
```bash
git merge upstream/master
```

### 4. Resolve any conflicts (if necessary) and commit the changes
```bash
git add *
git commit -m "upmerge <YYYY-MM-DD>"
```

### 5. Push the upmerged branch to your fork
```bash
git push origin v4.1.0
```

---

## 6. Create a Pull Request

Open a new pull request on GitHub:

- **Base:** `upstream/v4.1.0`
- **Compare:** `origin/v4.1.0`
- **Title:** `[Chore] Upmerge master into v4.1.0`
- **Description:** Optional – e.g. "Merged latest bug fixes from master into v4.1.0"

---

## Notes

- Perform upmerges regularly, e.g. after each relevant bug fix in `master`.
- Before merging, ensure both branches are up to date:
  ```bash
  git pull upstream/v4.1.0
  git pull upstream/master
  ```
  
---

## Benefits

- ✅ No need for a temporary merge branch
- ✅ Safe merging via pull request instead of direct pushes
- ✅ Full support for CI and code review
- ✅ Reproducible and team-friendly process
