# Merge Users extension for phpBB

Adds an ACP option to allow the merging of two users into one, with the main user taking ownership of all topics, attachments, PMs, etc. of both users.

[![Build Status](https://github.com/david63/mergeusers/workflows/Tests/badge.svg)](https://github.com/phpbb-extensions/david63/mergeusers)
[![License](https://poser.pugx.org/david63/mergeusers/license)](https://packagist.org/packages/david63/mergeusers)
[![Latest Stable Version](https://poser.pugx.org/david63/mergeusers/v/stable)](https://packagist.org/packages/david63/mergeusers)
[![Latest Unstable Version](https://poser.pugx.org/david63/mergeusers/v/unstable)](https://packagist.org/packages/david63/mergeusers)
[![Total Downloads](https://poser.pugx.org/david63/mergeusers/downloads)](https://packagist.org/packages/david63/mergeusers)
[![codecov](https://codecov.io/gh/david63/mergeusers/branch/master/graph/badge.svg?token=D2500PgRex)](https://codecov.io/gh/david63/mergeusers)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/04d287b5f56b403e909b38550d96a1ea)](https://www.codacy.com/manual/david63/mergeusers?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=david63/mergeusers&amp;utm_campaign=Badge_Grade)

[![Compatible](https://img.shields.io/badge/compatible-phpBB:3.2.x-blue.svg)](https://shields.io/)
[![Compatible](https://img.shields.io/badge/compatible-phpBB:3.3.x-blue.svg)](https://shields.io/)

## Minimum Requirements
* phpBB 3.3.0
* PHP 7.1.3

## Install
1. [Download the latest release](https://github.com/david63/mergeusers/archive/3.2.zip) and unzip it.
2. Unzip the downloaded release and copy it to the `ext` directory of your phpBB board.
3. Navigate in the ACP to `Customise -> Manage extensions`.
4. Look for `Merge Users` under the Disabled Extensions list and click its `Enable` link.

## Usage
1. Navigate in the ACP to `Users & Groups -> User utilities -> Merge Users`.

## Uninstall
1. Navigate in the ACP to `Customise -> Manage extensions`.
2. Click the `Disable` link for `Merge Users`.
3. To permanently uninstall, click `Delete Data`, then delete the mergeusers folder from `phpBB/ext/david63/`.

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

© 2020 - David Wood