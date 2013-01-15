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
    const HAS_COMMIT = "hg log -r %s -p";
    const HAS_TAG = "hg log -r %s -p";
    const HAS_BRANCH = "hg log -r %s -p";
    const CHECKOUT_COMMIT = "hg update -c %s";
    const CHECKOUT_TAG = "hg update -c %s";
    const CHECKOUT_BRANCH = "hg update -c %s";
    const EXCLUDE = ".hgignore";
    const LAST_COMMIT = 'hg log -1 --format="%H"';

}