<div class="wpup-pagination-wrap tablenav" v-if="total > 1">
	<div>

		<div class="tablenav-pages">

			<router-link v-if="page_number > 1" class="wpup-pagination-btn prev page-numbers" :to="{ name: 'pagination', params: { user: paginate_user, page_number: ( page_number - 1 ) }}">«</router-link>
			<router-link v-for="page in total" :class="pageClass(page)+' wpup-pagination-btn'" :to="{ name: 'pagination', params: { user: paginate_user, page_number: page }}">{{ page }}</router-link>
			<router-link v-if="page_number < total" class="wpup-pagination-btn next page-numbers" :to="{ name: 'pagination', params: { user: paginate_user, page_number: ( page_number + 1 ) }}">»</router-link> 

		</div>
		<div v-if="pagination_spinner" class="wpup-pagination-spinner wpup-spinner"></div>
	</div>
	<div class="wpup-clearfix"></div>
</div>