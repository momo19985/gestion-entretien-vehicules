</div>
<!-- /#page-wrapper -->

</div> 

<!-- Scroll to Top Button -->
<button id="scroll-to-top" title="Retour en haut">
    <i class="fa fa-chevron-up"></i>
</button>

<script>
// Scroll to top button
$(window).scroll(function() {
    if ($(this).scrollTop() > 200) {
        $('#scroll-to-top').addClass('visible');
    } else {
        $('#scroll-to-top').removeClass('visible');
    }
});

$('#scroll-to-top').click(function() {
    $('html, body').animate({scrollTop: 0}, 500, 'swing');
});

// Tooltip initialization
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

</body>

</html>
