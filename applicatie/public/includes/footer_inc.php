 <!--footer-->
 <footer class="page-footer grey">
    <div class="row">
        <div class="col s12">
            <div class="col s12 m6 l4"> 
                <img src="/media/E-bay.jpg" alt="hq-ebay" width="200" height="100">
                    <p>Wij zijn EenmaalAndermaal, de marktleider in het veilen van producten</p>
                    <p>Menlo Park 404</p>
                    <p>San Jose, California</p>
            </div>
            <div class="col s12 m6 l4 center">
                <h5 class="about-footer yellow-text">Over ons</h5>
                <ul>
                    <li><a class="yellow-text" href="../php/over-ons.php">Wie zijn wij?</a></li>
                    <li><a class="yellow-text" href="../php/ontwikkelaars.php">Ontwikkelaars</a></li>
                    <li><a class="yellow-text" href="../php/algemenevoorwaarden.php">Algemene Voorwaarden</a></li>
                    <li><a class="yellow-text" href="../php/contact.php">Contact</a></li>
                </ul>
            </div>

            <div class="col s12 hide-on-med-and-up center">
                <a href="https://www.instagram.com/"  class="fab fa-instagram fa-3x i yellow-text" style="margin-right: 20px;"></a>
                <a href="https://www.facebook.com/"  class="fab fa-facebook-f fa-3x i yellow-text" style="margin-right: 20px"></a>
                <a href="https://www.twitter.com/" class="fab fa-twitter fa-3x i yellow-text" style="margin-right: 20px"></a>
                <a href="https://www.linkedin.com/" class="fab fa-linkedin-in fa-3x i yellow-text" style="margin-right: 20px"></a>
             </div>

            <div class="col s6 m6 l4 hide-on-small-only center">
                <h5 class="socialmedia-footer yellow-text">Social Media</h5>
                <ul>
                    <li><a class="yellow-text" href="https://www.instagram.com/">Instagram</a></li>
                    <li><a class="yellow-text" href="https://www.facebook.com/">Facebook</a></li>
                    <li><a class="yellow-text" href="https://www.twitter.com/">Twitter</a></li>
                    <li><a class="yellow-text" href="https://www.linkedin.com/">Linkedin</a></li>
                </ul>
            </div>
            <div class="col s12 m10 offset-m1 l8 offset-l2">
                <h5 class="newspaper-footer yellow-text">Schrijf je in voor onze nieuwsbrief</h5>
                <form action="../functions/newsletter_mail.php" method="post" autocomplete="off">
                    <div class="input-field">
                        <input class="borderless-input rounded col s9 white" id="email-letter" type="email" name="input-letter_email" placeholder="example@email.com" required>
                    </div>
                    <div class="input-field">
                        <button class="btn-small col s3 waves-effect yellow admin-margin" type="submit" name="submit-letter">
                            <i class="material-icons center">mail</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="footer-copyright grey">
        <div class="container center">
            <div class="col s12">
                deze site is voor school doeleinden!
            </div>
            <div class="col s12">
                © alle rechten voorbehouden.
            </div>            
        </div>
    </div>
</footer>

<!-- font awesome icons -->
<script src="https://kit.fontawesome.com/7581d5f274.js" crossorigin="anonymous"></script> 

<!-- scripts src -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

<!-- own script -->
<script src="../script/script.js"></script>

<!-- jquery script -->
<?php if(isset($_SESSION["User"])){
    echo '
        <script>
        $(document).ready(function () {
            setupTimers();
        });
        $(document).on("click","#btnStayLoggedIn",function(){
            resetTimer();
        });
        </script>
    ';
}
else{
    echo '
    <script>
        $(document).ready(function () {
            stopTimers();
        });
    </script>
    ';
}

?>


<script>
    $(document).ready(function () {        
        $(".sidenav").sidenav();
        $(".parallax").parallax();
        $(".dropdown-trigger-top").dropdown({closeOnClick: false, coverTrigger: false});
        $(".dropdown-trigger-top2").dropdown({closeOnClick: false, container: $('nav')});
        $(".dropdown-trigger-side").dropdown();

        $(".dropdown-trigger-top2").hover(function() {
            var instance = M.Dropdown.getInstance(this);
            instance.open();

            let target =  $(this).data('target');
            let targetElement = $('#' + target);
            let offsetThisElement = $(this).offset().left;

            $('#' + target).css('left',  (offsetThisElement + $(this).outerWidth()) + 'px');

        });
        
        $(".dropdown-trigger-top2").mouseleave(function() {

            let target =  $(this).data('target');
            let targetElement = $('#' + target);

            if ($('#' + target + ':hover').length == 0) {
                var instance = M.Dropdown.getInstance(this);
                instance.close();
            }
 
        });

        $(".dropdown-trigger-top2").on("click", function() {
            console.log("check");
            let href = $(this).attr('href');
            window.location.href = href;

        });

        $("select").formSelect();
        $(".datepicker").datepicker({
            autoClose: true,           
            format: "dd-mm-yyyy",
            showClearBtn: true,
            showMonthAfterYear: true,
            yearRange: 100,
            minDate: new Date(1900,0,1),
            // maxDate: new Date(2021, 0, 1),
            setDefaultDate: true,
            defaultDate: new Date()
        });
        $(".materialboxed").materialbox(); 
        $(".tabs").tabs();    
        $('.timepicker').timepicker({
            twelveHour: false,
            defaultTime: "now"
        });
});
</script>
