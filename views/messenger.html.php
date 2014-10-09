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
                echo '<span>';
                echo $department->getName();
                echo '</span>';
                echo '<button type="button">';
                echo '<img src="assets/images/send.png"/>';
                echo '</button>';
                echo '</div>';
                echo '<ul class="contacts">';
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
                        echo '<button type="button">';
                        echo '<img src="assets/images/send.png"/>';
                        echo '</button>';
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
    <div class="welcome">
        <h1>Welcome, <?php $name = isset($current_user) ? explode(' ', $current_user->getName()) : array('Anonymous'); echo $name[0];?>!</h1>
    </div>
</div>