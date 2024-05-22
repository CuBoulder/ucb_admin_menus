# CU Boulder Admin Menus

All notable changes to this project will be documented in this file.

Repo : [GitHub Repository](https://github.com/CuBoulder/ucb_admin_menus)

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

- ### Removes broken how-to page link in add content page (v1.2.1)
  [Remove] Removes broken how-to page link in add content page. CuBoulder/tiamat-custom-entities#129; Resolves CuBoulder/ucb_admin_menus#27
---

- ### Styles 'Preview' Toolbar
  Adjusts and styles top Preview toolbar. Also removes old Gin CSS. 
  
  Resolves #23 
---

- ### Adds Alumni Class Notes to Node/Add
  Resolves #24 
---

- ### Update admin menu for collection item pages
  Helps close https://github.com/CuBoulder/tiamat-theme/issues/534.
  Adds menu link for collection item pages.
---

- ### Adds FAQ Page to Menus
  Adds the FAQ Content Type. Resolves https://github.com/CuBoulder/tiamat-theme/issues/620
  
  Includes:
  - tiamat-theme (issue/tiamat-theme/620) => https://github.com/CuBoulder/tiamat-theme/pull/641
  - custom-entities (issue/tiamat-theme/620) => https://github.com/CuBoulder/tiamat-custom-entities/pull/92
  - ucb-admin-menus (issue/tiamat-theme/620) => https://github.com/CuBoulder/ucb_admin_menus/pull/20
---

- ### Makes Admin Helpscout Beacon compatible with the Claro admin toolbar
  Resolves CuBoulder/ucb_admin_menus#17
---

- ### CU Boulder Admin Menus v1.2
  This update adds site-wide 403 page redirection, to redirect an anonymous user trying to access an administration or restricted page to the user login page. Resolves CuBoulder/ucb_admin_menus#14
---

- ### Completes in-content menu blocks
  Removes extra "HTML and style options" added by [Menu Block](https://www.drupal.org/project/menu_block).
  
  Sister PR in: [tiamat-theme](https://github.com/CuBoulder/tiamat-theme/pull/552), [tiamat10-project-template](https://github.com/CuBoulder/tiamat10-project-template/pull/25), [tiamat10-profile](https://github.com/CuBoulder/tiamat10-profile/pull/50)
  
  CuBoulder/tiamat-theme#267
  CuBoulder/tiamat-theme#528
---

- ### Adds "Issue" and "Issue Archive" to "Add content" page
  Resolves CuBoulder/ucb_admin_menus#10
---

- ### Modifies the help text under link URI fields in the admin content editor interface
  The help text under all link URI fields in the admin content editor interface has been updated to address concerns with the default help text causing confusion among users.
  
  Resolves CuBoulder/tiamat-theme#406
  
  Resolves CuBoulder/tiamat-theme#308 (last remaining work item)
---

- ### Adds stylistic modifications to the secondary administration toolbar
  This update:
  - Modifies the "Edit" button on the secondary administration toolbar to actually look like a button
  - Changes the background of the secondary administration toolbar to the off-white of administration pages, increasing noticeability of the toolbar
  
  Resolves CuBoulder/tiamat-theme#282; Author @TeddyBearX 
---

- ### Fixes malformed `core_version_requirement` causing Drupal 10 incompatibility
  Resolves CuBoulder/ucb_admin_menus#7
---

- ### Hides administration items that a user doesn't have access to
  This update includes these changes across several repos:
  - Hides inaccessible items from the Admin Toolbar by installing Admin Toolbar Links Access Filter
  - Hides inaccessible items from "Add content" and "CU Boulder site settings"
  
  CuBoulder/tiamat-theme#240; Author @TeddyBearX
  
  Sister PRs in: [ucb_site_configuration](https://github.com/CuBoulder/ucb_site_configuration/pull/18), [tiamat-profile](https://github.com/CuBoulder/tiamat-profile/pull/32)
---

- ### Adds the Admin Helpscout Beacon and help page redirects
  - Admin Helpscout Beacon and help page redirects moved to ucb_admin_menus (resolves CuBoulder/ucb_admin_menus#2).
  - Now indicates D10 compatibility.
  
  Sister PR in: [ucb_site_configuration](https://github.com/CuBoulder/ucb_site_configuration/pull/17)
---

- ### Adds `CHANGELOG.md` and workflow
  Resolves CuBoulder/ucb_admin_menus#3
---
