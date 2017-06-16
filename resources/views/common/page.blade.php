<!-- 分页 Start -->
<nav>
    <ul class="pagination">
        <!-- 上一页按钮 -->
        <li v-if="pagination.current_page > 1">
            <a href="#" aria-label="Previous"
               @click.prevent="changePage(pagination.current_page - 1)">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <!-- 过度页码 -->
        <li v-if="preOmit">
            <a>...</a>
        </li>
        <!-- 页码 -->
        <li v-for="page in pagesNumber"
            :class="[page == isActived ? 'active' : '' ]">
            <a href="#"
               @click.prevent="changePage(page)">@{{ page }}</a>
        </li>
        <!-- 过度页码 -->
        <li v-if="nextOmit">
            <a>...</a>
        </li>
        <!-- 下一页按钮 -->
        <li v-if="pagination.current_page < pagination.last_page">
            <a href="#" aria-label="Next"
               @click.prevent="changePage(pagination.current_page + 1)">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
<!-- 分页 End -->