<?php include 'partial/header.php';
if (is_admin())
{
	?>

    <section class="main-content">
        <div class="headerRegister">
            <h2><span class="dashicons dashicons-admin-users"></span> Wysąpił nieoczekiwany błąd! </h2>
        </div>
        <p>Nie masz wystarczających uprawnień do do przeglądania tej strony.
            Proszę wróć do storny Ustawień, aby przejść proces rejetracji w Codex360</p>

        <div class="btn">
            <a href="mailto:support@context360.net">
                <input class="button button-primary button-large" type="submit" id="submit"
                       value="Skontaktuj się z naszą pomocą">
            </a>
        </div>
    </section>

	<?php
}
else
{
	echo "<div class='notice notice-error'><br />Admin login error: <p>Sorry, you are not allowed to access this page</p></div>";
}

include 'partial/footer.php';