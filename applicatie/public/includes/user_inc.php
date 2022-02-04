<?php

  function getUsers($dbh, $searchQuery = null){

    if($searchQuery) {
      $query = $dbh->prepare("SELECT TOP 20 G.gebruikersnaam,
       G.mailbox, G.is_geblokkeerd
      FROM Gebruiker AS G
      WHERE is_beheerder IS NULL OR is_beheerder = 0
      AND G.gebruikersnaam LIKE '%'+?+'%'
      ");
      $query->execute(array($searchQuery));
    } else {
      $query = $dbh->prepare("SELECT TOP 20 G.gebruikersnaam,
      G.mailbox, G.is_geblokkeerd
      FROM Gebruiker AS G
      WHERE is_beheerder IS NULL OR is_beheerder = 0
      ");
      $query->execute();
    }

    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  function userCard($username, $mail, $blocked) {

    $buttonClass = !$blocked ? "red" : "green";

    echo <<<EOL
          <div class="col s12 m6 l4 xl4">
            <div class="row">
              <div class="col s12">
                <div class="card background-white darken-1">
                  <div class="card-content white-text">
                    <span class="card-title center text-bold black-text truncate" title="$username">$username</span>
                    <p class="center black-text truncate" title="$mail">$mail</p>
                  </div>
                  <form id="block" action="../includes/block.php" method="post" autocomplete="off">
                  <div class="card-action center-align">
                      <button class="btn waves-effect $buttonClass rounded s10 offset-s1" type="submit" name="submit-user-block"> 
          EOL;
                    if($blocked == 1){ 
                      echo 'Deblokkeren <i class="material-icons left">check</i>'; 
                    } else { 
                      echo 'Blokkeren <i class="material-icons left">block</i>'; 
                    }  
          echo <<<EOL
        
                        </button>
                        <input hidden type="text" placeholder="mail" id="input_mail" name="user-block-mail" value="$mail">
                        <input hidden type="text" placeholder="mail" id="input_mail" name="user-blocked" value="$blocked">
                    </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
    EOL;
  }
?>
