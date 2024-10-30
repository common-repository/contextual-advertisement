<?php

use Context360\Model\View\PlacementsView;

/** @var PlacementsView $data */

include 'partial/header.php';
if (is_admin())
{
	?>
    <section class="main-content">

        <div class="headerRegister">
            <h2><span class="dashicons dashicons-editor-code"></span> Zaawansowane</h2>
        </div>

        <div class="main-content-body">

            <div id="list">
                <form id="check-list" name="myForm">

                    <div class="registration-row">
                        <h2>Wyświetlaj reklamy w następujących miejscach </h2>
                        <p>Wybierz miejsca w których chcesz aby zostały wyświetlane reklamy.</p>
                        <div class="hr"></div>

						<?php

						$checkedPositions = $data->getPlacementWebsite()->getDisplayPositions();

						$numb1 = strpos($checkedPositions, '1') !== false ? 'checked="checked"' : '';
						$numb2 = strpos($checkedPositions, '2') !== false ? 'checked="checked"' : '';
						$numb3 = strpos($checkedPositions, '3') !== false ? 'checked="checked"' : '';
						$numb4 = strpos($checkedPositions, '4') !== false ? 'checked="checked"' : '';
						?>

                        <div class="check-list">
                            <p><input type="checkbox" name="formPlacementsDisplay" id="placementPosition1"
                                      value="1" <?php echo esc_attr("$numb1"); ?>/>
                                <label for="placementPosition1">Posts</label></p>
                            <p><input type="checkbox" name="formPlacementsDisplay" id="placementPosition2"
                                      value="2" <?php echo esc_attr("$numb2"); ?>/>
                                <label for="placementPosition2">Pages</label></p>
                            <p><input type="checkbox" name="formPlacementsDisplay" id="placementPosition3"
                                      value="3" <?php echo esc_attr("$numb3"); ?>/>
                                <label for="placementPosition3">Categories</label></p>
                            <p><input type="checkbox" name="formPlacementsDisplay" id="placementPosition4"
                                      value="4" <?php echo esc_attr("$numb4"); ?>/>
                                <label for="placementPosition4">Frontpage</label></p>
                        </div>

                        <div class="buttons-field">
                            <div class="btn">
                                <input class="button button-primary button-large" type="submit" id="submit"
                                       value="Zapisz zmiany ">
                            </div>
                            <div class="btn-all">
                                <input class="button button-all" type="button" value="Zaznacz wszystkie"
                                       onclick="checkAllCheckboxes('myForm',true);"/>
                                <input class="button button-all" type="button" value="odznacz wszystkie"
                                       onclick="checkAllCheckboxes('myForm',false);"/>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>

        <div class="break"></div>

        <div class="main-content-body">
            <div class="registration-row">
                <form id="placement-list" name="myForm">
                    <h2>Kody PLACEMENT ID</h2>
                    <p>Wybierz tylko jedną wartość placement, którą chcesz wyświetlać na stronie.</p>
                    <table class="widefat">

                        <tbody>
						<?php
						$numOfColumnsInRow = 5;

						/** @var PlacementsView $data */
						foreach ($data->getPlacements()->getPlacements() as $key => $placement)
						{
							$id = 'placement-' . $placement->getPlacementId();

							$shouldClosePreviousRow = $key % $numOfColumnsInRow === 0;
							if ($shouldClosePreviousRow && $key !== 0)
							{
								echo "</tr>";
							}

							if ($shouldClosePreviousRow)
							{

								echo '<tr><td>
                                    <input type="radio" id="' . esc_attr($id) . '"
                                            data-name="formPlacementsList" 
                                     
                                            value="' . esc_attr($placement->getPlacementId()) . '"
                                            disabled="disabled"
                                            ' . (esc_attr($placement->getIsDefault()) ? 'checked="checked"' : '') . '>
                                    <label for=". $id .">' . (esc_html($placement->getPlacementName())) . '</label>
                                    </td>';
							}
							else
							{
								echo '<td>
                                    <input type="radio" id="' . esc_attr($id) . '" 
                                    data-name="formPlacementsList" 
                                    value="' . (esc_attr($placement->getPlacementId())) . '"
                                    disabled="disabled"
                                    ' . (esc_attr($placement->getIsDefault()) ? 'checked="checked"' : '') . '>
                                    <label for=" . $id . ">' . (esc_html($placement->getPlacementName())) . '</label>
                                    </td>';
							}
						}
						if (count($data->getPlacements()->getPlacements()) > 0)
						{
							echo "</tr>";
						}
						?>
                        </tbody>
                    </table>

                    <div class="buttons-field">
                        <div class="btn">
                            <input class="button button-primary button-large" type="submit"
                                   id="submit-placementid"
                                   value="Zapisz zmiany ">
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>

    <script>
        function checkAllCheckboxes(formName, choice) {
            var checkboxList = document.forms[formName].elements;
            for (var i = 0; i < checkboxList.length; i++) {
                if (checkboxList[i].type === "checkbox") {
                    checkboxList[i].checked = choice;
                }
            }
        }
    </script>

	<?php include 'partial/aside.php';
}
else
{
	echo "<div class='notice notice-error'><br />Admin login error: <p>Sorry, you are not allowed to access this page</p></div>";
}

include 'partial/footer.php';