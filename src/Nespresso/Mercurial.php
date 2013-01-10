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
    static $SOURCE = "Mercurial";     
    static $CLONE = "hg clone %s %s";
    static $HAS_COMMIT = "show %s";
    static $HAS_TAG = "ls-tree %s";
    static $HAS_BRANCH = "show-branch origin/%s";
    static $CHECKOUT_COMMIT = "hg checkout %s";
    static $CHECKOUT_TAG = "hg checkout %s";
    static $CHECKOUT_BRANCH = "hg checkout %s";
    static $EXCLUDE = ".hgignore";
    static $LAST_COMMIT = 'hg log -1 --format="%H"';

}