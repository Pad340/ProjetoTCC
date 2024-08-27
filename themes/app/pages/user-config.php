<div class="user-config">
    <h1>Configurações da conta</h1>

    <?php
    if (!$session->has('authSeller')) {
        ?>
        <h2>Criar uma conta de vendedor</h2>
        <form action method="post" autocomplete="off">
            <label for="seller_name">Nome de vendedor</label>
            <input type="text" name="seller_name" id="seller_name" required>

            <button type="submit" name="seller_btn">Cadastrar</button>
        </form>
        <?php
    }
    ?>
</div>