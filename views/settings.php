<?php include 'partial/header.php';
if (is_admin())
{
	?>

    <section class="main-content">
        <div class="headerRegister">
            <h2><span class="dashicons dashicons-admin-users"></span> Formularz rejestracyjny</h2>
        </div>

        <div class="main-content-body">
            <div class="registration-row">
                <form id="form">
                    <h3>1. Podaj adres e-mail</h3>

                    <div class="email">
                        <div class="capt_style">
                            <div id="captcha" class="g-recaptcha" data-theme="dark"
                                 data-sitekey="Your Site Key"></div>
                        </div>

                        <p>
                            <label for="user_pass">
                                <span>Podaj swój adres e-mail:</span>
                                <input type="email" name="user email" id="email"
                                       class="input user input-disabled" size="20" disabled>
                            </label>
                        </p>

                    </div>

                    <h3>2. Wybierz interesujące cię kategorie</h3>
                    <p>Wbierz co najmniej jedną z kategorii</p>

                    <div class="categories disabled">
						<?php include 'partial/settings/categories.php'; ?>
                    </div>

                    <div class="btn" id="sendIt">
                        <input class="button button-primary button-large" type="submit" id="submit"
                               value="Załóż nowe konto" disabled>
                    </div>


                </form>
            </div>
        </div>


    </section>

	<?php include 'partial/aside.php';
}
else
{
	echo "<div class='notice notice-error'><br />Admin login error: <p>Sorry, you are not allowed to access this page</p></div>";
}

include 'partial/footer.php';