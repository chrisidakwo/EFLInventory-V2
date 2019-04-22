<section class="sidebar">
    <div class="menu-items">
        <div>
            <a href="{{ route("home") }}" class="{{ active("home") }}" title="Dashboard">
                <i class="mdi mdi-home"></i>
            </a>
        </div>
        <a href="{{ route("categories") }}" class="{{ active("categories") }}" title="Categories"><i class="mdi mdi-tag-multiple"></i></a>
        <div class="has-dropdown">
            <a href="{{ route("inventory") }}" title="Inventory">
                <i class="mdi mdi-store"></i>
            </a>
            <div class="sidebar-menu-dropdown">
                <a href="#" class="dropdown-link">Link 1</a>
                <a href="#" class="dropdown-link">Link 2</a>
                <a href="#" class="dropdown-link">Link 3</a>
            </div>
        </div>
        <a href="{{ route("users") }}" title="Users"><i class="mdi mdi-account-multiple"></i></a>
        <a href="{{ route("pos") }}" title="Point-of-Sale"><i class="mdi mdi-calculator"></i></a>
    </div>
</section>
