<?php
?>

<div class="blogs-page">
    <div class="blogs-page-head">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-xl-7 xs-last">
                <div class="images-holder">
                    <div class="image first">
                    
                        <img src="<?php echo RUVOD_PLUGIN_DIR.'/assets/images/blogs1.png'; ?>" alt="" class="shadow">
                    </div>
                    <div class="image second shadow">
                        <img src="<?php echo RUVOD_PLUGIN_DIR.'/assets/images/blogs2.png'; ?>" alt="">
                    </div>
                    <div class="image third shadow">
                        <img src="<?php echo RUVOD_PLUGIN_DIR.'/assets/images/blogs3.png'; ?>" alt="">
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-xl-5">
                <div class="desc">
                    <h6 class="sub-title uppercase">
                        Блог на RUVOD
                    </h6>
                    <h6 class="main-title">
                        Ваш голос в <br>
                        среде партнеров <br>
                        и профессионалов  <br>
                        индустрии
                    </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="blogs-page-action loader traditional">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h6 class="main-title">
                    Завести блог на ruvod
                </h6>
                <div class="form">
                    <form  class="ajax-form"  action="/wp-admin/admin-ajax.php" method="post">
                        <div style="display:none;" class="alert alert-danger" role="alert"></div>
                        <div style="display:none;" class="alert alert-success" role="alert"></div>
                        <input type="hidden" name="action" value="blogs_feedback">
                        <input required type="email" name="email" class="form-control inline" placeholder="Ваш email">
                        <button type="submit" class="btn btn-xl inline submit btn-primary">
                            ОТПРАВИТЬ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="blogs-page-description">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-offset-1 col-lg-10  col-xl-offset-2 col-xl-8">
                <h6 class="sub-title uppercase">
                    Возможности сервиса:
                </h6>
                <div class="desc-items">
                    <div class="row desc-item">
                        <div class="col-xs-12 col-sm-3">
                            <h6 class="sub-title">
                                Блог
                            </h6>
                        </div>
                        <div class="col-xs-12 col-sm-9 desc-text">
                            Неограниченное количество публикаций 
                            <br>
                            Раз в неделю вывод публикации на главную страницу RUVOD
                            <br>
                            Публикация в Телеграм-канал, Twitter, FB и VK
                        </div>
                    </div>
                    <div class="row desc-item">
                        <div class="col-xs-12 col-sm-3">
                            <h6 class="sub-title">
                                Вакансии
                            </h6>
                        </div>
                        <div class="col-xs-12 col-sm-9 desc-text">
                            Размещение вакансий вашей компании в специализированном HR-разделе
                        </div>
                    </div>
                    <div class="row desc-item">
                        <div class="col-xs-12 col-sm-3">
                            <h6 class="sub-title">
                                Аналитика
                            </h6>
                        </div>
                        <div class="col-xs-12 col-sm-9 desc-text">
                            Дважды в год индустриальнный аналитический отчет
                        </div>
                    </div>
                </div>

                <h6 class="sub-title cards-title uppercase">
                    Почему это нужно ВАМ:
                </h6>
                <div class="desc-cards">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-lg-4">
                            <div class="desc-card">
                                Публикация достоверных новостей из первых рук
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-lg-4">
                            <div class="desc-card">
                                Автоматическая публикация в СМИ агрегаторы
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-lg-4">
                            <div class="desc-card">
                                Возможность найти новых партнеров и опытных сотрудников
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="blogs-page-action loader traditional">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h6 class="main-title">
                    Завести блог на ruvod
                </h6>
                <div class="form">
                    <form  class="ajax-form"  action="/wp-admin/admin-ajax.php" method="post">
                        <div style="display:none;" class="alert alert-danger" role="alert"></div>
                        <div style="display:none;" class="alert alert-success" role="alert"></div>
                        <input type="hidden" name="action" value="blogs_feedback">
                        <input required type="email" name="email" class="form-control inline" placeholder="Ваш email">
                        <button type="submit" class="btn btn-xl inline submit btn-primary">
                            ОТПРАВИТЬ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>