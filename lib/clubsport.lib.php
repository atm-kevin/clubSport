<?php
/* Copyright (C) 2020 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file		lib/clubsport.lib.php
 *	\ingroup	clubsport
 *	\brief		This file is an example module library
 *				Put some comments here
 */

/**
 * @return array
 */
function clubsportAdminPrepareHead()
{
    global $langs, $conf;

    $langs->load('clubsport@clubsport');

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/clubsport/admin/clubsport_setup.php", 1);
    $head[$h][1] = $langs->trans("Parameters");
    $head[$h][2] = 'settings';
    $h++;
    $head[$h][0] = dol_buildpath("/clubsport/admin/clubsport_extrafields.php", 1);
    $head[$h][1] = $langs->trans("ExtraFields");
    $head[$h][2] = 'extrafields';
    $h++;
    $head[$h][0] = dol_buildpath("/clubsport/admin/clubsport_about.php", 1);
    $head[$h][1] = $langs->trans("About");
    $head[$h][2] = 'about';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    //$this->tabs = array(
    //	'entity:+tabname:Title:@clubsport:/clubsport/mypage.php?id=__ID__'
    //); // to add new tab
    //$this->tabs = array(
    //	'entity:-tabname:Title:@clubsport:/clubsport/mypage.php?id=__ID__'
    //); // to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'clubsport');

    return $head;
}

/**
 * Return array of tabs to used on pages for third parties cards.
 *
 * @param 	ClubSport	$object		Object company shown
 * @return 	array				Array of tabs
 */
function clubsport_prepare_head(ClubSport $object)
{
    global $langs, $conf;
    $h = 0;
    $head = array();
    $head[$h][0] = dol_buildpath('/clubsport/card.php', 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans("ClubSportCard");
    $head[$h][2] = 'card';
    $h++;
	
	// Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@clubsport:/clubsport/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@clubsport:/clubsport/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'clubsport');
	
	return $head;
}

/**
 * @param Form      $form       Form object
 * @param ClubSport  $object     ClubSport object
 * @param string    $action     Triggered action
 * @return string
 */
function getFormConfirmClubSport($form, $object, $action)
{
    global $langs, $user;

    $formconfirm = '';

    if ($action === 'valid' && !empty($user->rights->clubsport->write))
    {
        $body = $langs->trans('ConfirmValidateClubSportBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmValidateClubSportTitle'), $body, 'confirm_validate', '', 0, 1);
    }
    elseif ($action === 'accept' && !empty($user->rights->clubsport->write))
    {
        $body = $langs->trans('ConfirmAcceptClubSportBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmAcceptClubSportTitle'), $body, 'confirm_accept', '', 0, 1);
    }
    elseif ($action === 'refuse' && !empty($user->rights->clubsport->write))
    {
        $body = $langs->trans('ConfirmRefuseClubSportBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmRefuseClubSportTitle'), $body, 'confirm_refuse', '', 0, 1);
    }
    elseif ($action === 'reopen' && !empty($user->rights->clubsport->write))
    {
        $body = $langs->trans('ConfirmReopenClubSportBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmReopenClubSportTitle'), $body, 'confirm_refuse', '', 0, 1);
    }
    elseif ($action === 'delete' && !empty($user->rights->clubsport->write))
    {
        $body = $langs->trans('ConfirmDeleteClubSportBody');
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmDeleteClubSportTitle'), $body, 'confirm_delete', '', 0, 1);
    }
    elseif ($action === 'clone' && !empty($user->rights->clubsport->write))
    {
        $body = $langs->trans('ConfirmCloneClubSportBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmCloneClubSportTitle'), $body, 'confirm_clone', '', 0, 1);
    }
    elseif ($action === 'cancel' && !empty($user->rights->clubsport->write))
    {
        $body = $langs->trans('ConfirmCancelClubSportBody', $object->ref);
        $formconfirm = $form->formconfirm($_SERVER['PHP_SELF'] . '?id=' . $object->id, $langs->trans('ConfirmCancelClubSportTitle'), $body, 'confirm_cancel', '', 0, 1);
    }

    return $formconfirm;
}
