<div>
	<router-link  :to="{ name: 'members_initial_view' }"><?php _e( 'Back', 'wpup' ); ?></router-link>
	<wpup-view-individual-user-profile :user_id="user_id"></wpup-view-individual-user-profile>
</div>