site:
	ansible-playbook -i hosts.yml site.yml -v

renew-certificates:
	ansible-playbook -i hosts.yml renew-certificates.yml -v

authorize:
	ansible-playbook -i hosts.yml authorize.yml -v

generate_deploy_key:
	ssh-keygen -q -t rsa -N '' -f files/deploy_rsa

authorize-deploy:
	ansible-playbook -i hosts.yml authorize-deploy.yml -v

docker-login:
	ansible-playbook -i hosts.yml docker-login.yml -v
