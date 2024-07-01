</div>

<footer class="bg-dark text-light p-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-center"><a href="mentions_legales.php">Mentions légales</a></h5>
            </div>
            <div class="col-md-6">
                <h5 class="text-center"><a href="cgv.php">Conditions générales de vente</a></h5>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12 text-center">
                <div class="badge text-wrap p-3">Copyright &copy; TROC / ANNONCES <?= date('Y')?></div>
            </div>
        </div>
    </div>
</footer>

<script>
    function updatePrice(value) {
        document.getElementById('prixOutput').innerText = value + '€';
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>


   
</body>
</html>