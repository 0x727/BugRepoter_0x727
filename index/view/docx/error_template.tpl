{include file="../header.tpl"}
	<div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
						<h2>
							<p>请安装onlyoffice模块。</p>
						</h2>
						<h4>
							<p>docker run -i -t -d -p 8000:80 onlyoffice/documentserver</p>
							<p>docker exec $(docker ps -a | grep -E "(onlyoffice/documentserver)" | awk '{
							print $1
							}') rm -rf /var/www/onlyoffice/documentserver-example</p>
							<p>docker exec $(docker ps -a | grep -E "(onlyoffice/documentserver)" | awk '{
							print $1
							}') /usr/sbin/nginx</p>
						</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
{include file="../footer.tpl"}