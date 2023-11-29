<div class="popup">
    <div class="contenuPopup">
        <form method="post">
				<input type="submit" value="" class="boutonQuitPopup">
                <input type="hidden" name="popup" value="">
		</form>
        <p class="titrePopup">Se connecter <?php if((isset($_SESSION['tempIsAdmin']) and $_SESSION['tempIsAdmin']))
                                            {echo '(Admin)';}?></p>
        <div>
            <form class="formPopup" method="post">
                <div>
                    <label for="mail">Mail :</label>
                    <input class="zoneDeTextePopup" type="text" name="mail" required>
                    <input type="hidden" value='0' name="formClicked">
                    <input type="hidden" value='sign_in' name="popup">
                </div>
                <div>
                    <label for="pwd">Mot de passe :</label>
                    <input class="zoneDeTextePopup" type="text" name="pwd" required>
                </div>
                <div>
                    <?php
                    if (isset($_SESSION['erreur'])) {
                        $erreur = $_SESSION['erreur'];
                        echo '<p class="erreur">'.$erreur.'</p>';
                        unset($_SESSION['erreur']);
                    }
                    ?>
                </div>
                <input class="boutonPopup" type="submit" value="se connecter">
            </form>
            <?php
            if (isset($_POST['formClicked'])){
                if((isset($_SESSION['tempIsAdmin']) and $_SESSION['tempIsAdmin'])){
                    require 'traitements/traitement_formulaire_sign_in_admin.php';
                }else{
                    require 'traitements/traitement_formulaire_sign_in.php';
                }
                unset($_POST['formClicked']);
                unset($_SESSION['tempIsAdmin']);
            }
            ?>
            <div>
                <form method="post">
					<?php if((isset($_SESSION['tempIsAdmin']) and $_SESSION['tempIsAdmin'])){?>
                        <input type="submit" value="Se connecter en tant qu'utilisateur lambda" class="lienPopup">
                        <input type="hidden" name="popup" value="sign_in">
                    <?php }else{ ?> 
                        <input type="submit" value="Se connecter en tant qu'administrateur" class="lienPopup">
                        <input type="hidden" name="popup" value="sign_in_admin">
                    <?php } ?>
			    </form>
            </div>
        </div>
        <div>
            <form method="post">
				<input type="submit" value="Mot de passe oublié ?" class="lienPopup">
                <input type="hidden" name="popup" value="reset_mdp">
			</form>
        </div>
        <div class="alignementCentreCoteACote">
            <p class="text">Vous n'avez pas de compte ?</p>
            <form method="post">
				<input type="submit" value="S'incrire" class="lienPopup">
                <input type="hidden" name="popup" value="pre_sign_up">
			</form>
        </div>
    </div>
</div>
