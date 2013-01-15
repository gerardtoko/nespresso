<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso;

/**
 * Description of Task
 *
 * @author gerardtoko
 */
class Git
{

    const SOURCE = "Git";
    const CLONE_SCM = "git clone %s %s";
    const HAS_COMMIT = "git show %s";
    const HAS_TAG = "git show %s";
    const HAS_BRANCH = "git show %s";
    const CHECKOUT_COMMIT = "git checkout %s";
    const CHECKOUT_TAG = "git checkout %s";
    const CHECKOUT_BRANCH = "git checkout %s";
    const EXCLUDE = ".gitignore";
    const LAST_COMMIT = 'git log -1 --format="%H"';

}