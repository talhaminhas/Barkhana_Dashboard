<div class="user-list">


    <?php
    $count = 0;

    if (!empty($favourites) && count($favourites->result()) > 0) {
        $userProductArray = array(); // Initialize 2D array

        foreach ($favourites->result() as $favourite) {
            $user_id = $favourite->user_id;
            $product_id = $favourite->product_id;

            // Check if the user ID exists as a key in the 2D array
            if (array_key_exists($user_id, $userProductArray)) {
                // If the user ID already exists, append the product name to the existing array
                $userProductArray[$user_id][] = $this->Product->get_one($product_id)->name;
            } else {
                // If the user ID doesn't exist, create a new array for it and add the product name
                $userProductArray[$user_id] = array($this->Product->get_one($product_id)->name);
            }
        }

        // Sort the userProductArray by user names (keys)
        ksort($userProductArray);
    }
    ?>

    <?php if (!empty($userProductArray)): ?>
        <?php foreach ($userProductArray as $user_id => $product_names): ?>
            <ol class="user-item">
                <div class="user-name"><?php echo ($count + 1) . '. ' . $this->User->get_one($user_id)->user_name; ?></div>
                <ol class="favorites-list">
                    <?php
                    // Sort the product names alphabetically
                    sort($product_names);
                    foreach ($product_names as $index => $product_name):
                    ?>
                        <li><?php echo ($index + 1) . '. ' . $product_name; ?></li>
                    <?php endforeach; ?>
                </ol>
            </ol>
            <?php $count++; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php $this->load->view($template_path . '/partials/no_data'); ?>
    <?php endif; ?>
</div>



<style>

	.user-list {
		list-style-type: none;
		padding: 0;
        margin-left: 20px;
        border: 1px solid #ccc; /* Add border around product items */
        padding: 5px; /* Add padding to separate the border from content */
        border-radius: 5px; /* Add rounded corners to the border */
        background-color: #fff;
	}

	.user-item {
    cursor: pointer;
    padding: 5px;
    border: 1px solid #ccc;
    margin-bottom: 5px;
    transition: background-color 0.3s;
    border-radius: 5px; /* Add rounded corners */
    background-color: #6b6a6a; /* Add background color */
	}


	

	.user-name {
		font-weight: bold;
		margin-bottom: 5px;
		text-transform: uppercase;
	}

	.favorites-list {
    list-style-type: none;
    padding: 0;
    display: none;
    }

    /* Style for even rows */
    .favorites-list li:nth-child(even) {
        background-color: #f0f0f0; /* Add background color for even rows */
        border: 1px solid #ccc; /* Add border around rows */
        padding: 5px; /* Add padding to separate the border from content */
        border-radius: 5px; /* Add rounded corners to the border */
        margin-bottom: 5px;
    }

    /* Style for odd rows */
    .favorites-list li:nth-child(odd) {
        background-color: #bac2b8; /* Add background color for odd rows */
        border: 1px solid #ccc; /* Add border around rows */
        padding: 5px; /* Add padding to separate the border from content */
        border-radius: 5px; /* Add rounded corners to the border */
        margin-bottom: 5px;
    }

</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const userItems = document.querySelectorAll(".user-item");

        userItems.forEach((userItem) => {
            userItem.addEventListener("click", function () {
                const favoritesList = this.querySelector(".favorites-list");

                if (favoritesList.style.display === "none") {
                    favoritesList.style.display = "block";
                } else {
                    favoritesList.style.display = "none";
                }
            });
        });
    });
</script>
