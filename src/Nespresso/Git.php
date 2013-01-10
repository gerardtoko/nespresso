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

    static $SOURCE = "Git";
    static $CLONE = "git clone %s %s";
    static $HAS_COMMIT = "show %s";
    static $HAS_TAG = "ls-tree %s";
    static $HAS_BRANCH = "show-branch origin/%s";
    static $CHECKOUT_COMMIT = "git checkout %s";
    static $CHECKOUT_TAG = "git checkout %s";
    static $CHECKOUT_BRANCH = "git checkout %s";
    static $EXCLUDE = ".gitignore";
    static $LAST_COMMIT = 'git log -1 --format="%H"';

}