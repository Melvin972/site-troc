<!-- Fermeture des balises -->
</div>
    </div>
</div>

<!-- Import des scripts jQuery et Bootstrap -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Import des scripts de DataTables -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

<!-- Script pour basculer le menu -->
<script>
  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
</script>

<!-- Initialisation de DataTables -->
<script>
  $('.mydatatable').DataTable();
</script>

<!-- Script pour confirmer une action avec une boîte de dialogue -->
<script>
    $(document).ready(function() {
    $('a[data-confirm]').click(function(ev) {
        var href = $(this).attr('href');

        if (!$('#dataConfirmModal').length) {
            // Création de la boîte de dialogue de confirmation
            $('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">Please Confirm</h3></div><div class="modal-body"></div><div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button><a class="btn btn-primary" id="dataConfirmOK">OK</a></div></div>');
        } 
        // Remplissage de la boîte de dialogue avec le message de confirmation
        $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
        $('#dataConfirmOK').attr('href', href);
        $('#dataConfirmModal').modal({show:true});
        return false;
    });
});

// Préparation de la boîte de dialogue pour la suppression d'un élément
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

</script>

<!-- Script pour afficher une modal lors du chargement de la page -->
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModalCommand').modal('show');
    });
</script>

<!-- Script pour afficher une modal lors du chargement de la page -->
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModalDetailsCommand').modal('show');
    });
</script>

<!-- Script pour afficher une modal lors du chargement de la page -->
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModalUsers').modal('show');
    });
</script>

<!-- Script pour afficher la description complète lorsque l'utilisateur clique sur "Lire la suite" -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleDescriptions = document.querySelectorAll('.toggle-description');

        toggleDescriptions.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const descriptionLongueSpan = toggle.closest('.description-longue');
                const hiddenDescription = toggle.dataset.hiddenDescription;

                descriptionLongueSpan.innerHTML = hiddenDescription;
            });
        });
    });
</script>

</body>

</html>
