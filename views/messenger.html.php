<div class="content">
    <div class="contacts_list">
        <ul class="departments">
        <?php
        if (!empty($contacts) and !empty($departments)) {
            foreach ($departments as $department) {
                echo '<li class="department">';
                echo '<div class="department_id" style="display: none;">';
                echo $department->getId();
                echo '</div>';
                echo '<div class="department_name">';
                echo $department->getName();
                echo '</div>';
                echo '<ul class="contacts">';
                //Placeholder for send to department button
                foreach ($contacts as $contact) {
                    if ($contact->getDepartmentId() === $department->getId()) {
                        echo '<li class="contact">';
                        echo '<div class="contact_id" style="display: none;">';
                        echo $contact->getId();
                        echo '</div>';
                        echo '<div class="contact_name">';
                        echo $contact->getName();
                        echo '</div>';
                        echo '<div class="contact_title">';
                        echo $contact->getTitle();
                        echo '</div>';
                        //Placeholder for send to contact button
                        echo '</li>';
                    }
                }
                echo '</ul>';
                echo '</li>';
            }
        }
        ?>
        </ul>
    </div>
    <div class="main">
        <!-- Main block placeholder (will be rendered using jQuery) -->
    </div>
</div>