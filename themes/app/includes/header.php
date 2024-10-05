<header>
    <nav class="navdiv">
        <a href="<?= url('app/home') ?>">
            <img class="headerLogo" src="/projetotcc/storage/images/whiteLogo.png" alt="header-logo" height="100">
        </a>
        <ul>
            <li><a href="<?= url('app/home') ?>">Home</a></li>

            <?php if ($session->has('authSeller')) { ?>
                <li><a href="<?= url('app/products') ?>">Seus produtos</a></li>
            <?php } ?>

            <li>
                <div class="cart-div">
                    <a href="<?= url('app/cart') ?>">Carrinho</a>
                    <img class="cartIcon" src="/projetotcc/storage/images/carrinhoIcon.png" alt="cart-icon">
                </div>
            </li>

            <li>
                <div class="dropdown">
                    <div class="profile-div">
                        <h1>Conta</h1>
                        <img class="profileIcon" src="/projetotcc/storage/images/profileIcon.png" alt="profile-icon">
                    </div>
                    <div class="dropdown-content">
                        <a href="<?= url('app/user-config') ?>">Configurações</a>
                        <a href="<?= url('app/logout') ?>">Sair</a>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</header>