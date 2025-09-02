<!-- Main Task Section -->
<div class="container">
    <div class="row pb-2 mt-3">
        <!-- Task Table Column -->
        <div class="col-sm-12 col-md-12 col-lg-9 py-1">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between mb-1">
                        <div class="p-2 mb-1">
                            <h4 class="fw-bold border-bottom">Task List</h4>
                        </div>  
                        <div class="py-2 mb-1">
                            <input class="form-control w-100" id="search-control-task" type="search" placeholder="Search">
                        </div>
                    </div>
                    <div class="table-responsive">
                         <!-- Spinner for Task -->
                   	 <div id="task-spinner-table" class="d-flex justify-content-center my-5">
                        	<div class="spinner-border text-primary" role="status">
                            		<span class="sr-only"></span>
                        	</div>
                    	</div>                        
			<table id="table-task">
                            <!-- Dynamic Table -->
                        </table>
                    </div>
                </div>
            </div>
        </div>  
        <!-- Task Nav Column -->
        <div class="col-sm-12 col-md-12 col-lg-3 py-1 text-center">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="border-bottom pb-2 fs-5">Task Navigation</h4>
                    <a
                        class="user_button_task btn p-2 my-2 w-100 h-100 shadow text-light" 
                        href="<?= $_ENV['HOME_URL'] ?>/task/handler"
                    >Add Task</a>
                </div>
            </div>
        </div> 
    </div>
</div>

<script src="<?= $_ENV['HOME_URL'] ?>/js/task/task.js"></script>
