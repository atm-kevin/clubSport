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
 * \file    class/actions_clubsport.class.php
 * \ingroup clubsport
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

/**
 * Class ActionsClubSport
 */
class ActionsClubSport
{
    /**
     * @var DoliDb		Database handler (result of a new DoliDB)
     */
    public $db;

	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
     * @param DoliDB    $db    Database connector
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function doActions($parameters, &$object, &$action, $hookmanager)
	{
		global $langs, $conf, $user, $mc;
		$form = new Form($this->db);

		if ($action == 'confirm_delete') {
			$sql = "SELECT COUNT(*) as c FROM " . MAIN_DB_PREFIX . "clubsport WHERE fk_product =" . $object->id;
			$resql = $this->db->query($sql);

			if ($resql) {
				if ($resql > 0) {
					$obj = $this->db->fetch_object($resql);
					if ($obj->c > 0) {
						setEventMessage("Il y a encore " . $obj->c . " session(s) liées à votre produit");
						header('location: ' . DOL_URL_ROOT . '/product/card.php?id=' . $object->id . '&action=ask_delete_clubsportchild');
						exit;
					}
				};
			}
		}
		if ($action == 'confirm_delete_double') {
			$action = 'confirm_delete';
		}
	}
	/**
	 * Overloading the addMoreActionsButtons function : replacing the parent's function with the one below
	 *
	 * @param array()         $parameters     Hook metadatas (context, etc...)
	 * @param CommonObject $object      The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param string       $action      Current action (if set). Generally create or edit or null
	 * @param HookManager  $hookmanager Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function addMoreActionsButtons($parameters, &$object, &$action, $hookmanager)
	{
		global $conf, $user, $langs;

		$context = explode(':', $parameters['context']);

		if (in_array('productcard', $context) || in_array('ordercard', $context) || in_array('invoicecard', $context)) {

			/** @var CommonObject $object */
			$param = array(
				"attr" => array(
					"title" => "Test",
					'class' => 'classfortooltip'
				)
			);

			// On stocke l'unité de valeur de la durée (dans la BDD, "i" == "minutes" et "h" == heure)
			$mesure = substr($object->duration, -1) == 'i' ? 'minutes' : substr($object->duration, -1);

			print dolGetButtonAction("$langs->trans('SessionCreate')", '<i class="fa fa-plus" aria-hidden="true"></i> Créer une session', 'default',
				dol_buildpath('/custom/clubsport/card.php?fk_product='
					. $object->id . "&date_debut=" . date("Y-m-d H:i:s") . "&date_fin="
					. date('Y-m-d H:i:s', strtotime('+' . substr($object->duration, 0, strlen($object->duration - 1)) . ' ' . $mesure)),
					1)
				//  On pré-remplir aussi la date d'échéance à la création d'une session par le biais du produit/service
//					. "&date_warning=" . date('Y-m-d H:i:s', strtotime('+10 days' . substr($object->duration, 0, strlen($object->duration - 1)) . ' ' . $mesure))
					. "&action=create&label="
					. substr($object->ref, 0, -2), 'button-clubsport-creation', $user->rights->clubsport->write, $param);

			$form = new Form($this->db);
			if ($action == 'ask_delete_clubsportchild') {
				print $form->formconfirm($_SERVER["PHP_SELF"]
					. '?id=' . $object->id, $langs->trans('clubsport_doublecheck_delete')
					, $langs->trans('clubsport_CheckConfirmDeleteProduct', $object->ref), 'confirm_delete_double'
					, 'yes', 'action-delete', 350, 300);
			}
		};
	}
	public function formObjectOptions($parameters, &$object, &$action, $idUser)
	{
		// Méthode pour ajouter des lignes/du code à l'intérieur dans l'affichage principal (card...)
		global $db;

		$form = new Form($db);
		$sql = "SELECT COUNT(*) as c FROM " . MAIN_DB_PREFIX . "clubsport WHERE fk_product = " . $object->id;
		$resql = $this->db->query($sql);

		if ($resql) {
			if ($resql > 0) {
				$obj = $this->db->fetch_object($resql);

				print '<td>';
				print 'Nombre de sessions liées à ce service';
				print '</td>';
				print '<td>';
				print $obj->c;
				print '</td>';
			};
		}
	}


}
