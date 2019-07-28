<template>
	<div>
		<ul>
			<li>
				<router-link  
		        	:to="{ 
		        		name: 'my_profile'
		        	}">
		        	My Profile
		        </router-link>
		    </li>
		    <li>

		    	<router-link  
	                	:to="{ 
	                		name: 'users'
	                	}">
	                	Users
	                </router-link>
	        </li>
	    </ul>
	</div>
</template>