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
class Mercurial
{

    const SOURCE = "Mercurial";
    const CLONE_SCM = "hg clone %s %s";
    const HAS_COMMIT = "show %s";
    const HAS_TAG = "ls-tree %s";
    const HAS_BRANCH = "show-branch origin/%s";
    const CHECKOUT_COMMIT = "hg checkout %s";
    const CHECKOUT_TAG = "hg checkout %s";
    const CHECKOUT_BRANCH = "hg checkout %s";
    const EXCLUDE = ".hgignore";
    const LAST_COMMIT = 'hg log -1 --format="%H"';

}