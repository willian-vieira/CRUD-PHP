<?php

    Class Pessoa {
        private $pdo;

        //CONEXAO COM BANCO DE DADOS
        public function __construct ($dbname, $host, $user, $senha)
        {
            try {
                $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$senha);
                
            } catch (PDOExcepetion $e) {
                echo "Erro com banco de dados: ".$e->getMessage();
                exit();
            } catch (Exception $e) {
                echo "Erro generico: ".$e->getMessage();
                exit();
            }
        } 

        //FUNÇÃO DE BUSCAR DADOS E EXIBIR NO CANTO DIRETO DA TELA
        public function buscarDados()
        {
            $res = array();
            $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
            $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }

        //FUNÇÃO DE CADASTRAR INFORMAÇÕES DE PESSOAS NO BANCO DE DADOS
        public function cadastrarPessoa($nome, $telefone, $email)
        {
            //AO CADASTRAR IRÁ VERIFICAR SE JÁ POSSUI O EMAIL CADASTRADO
            $cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE email = :e");
            $cmd->bindValue(":e",$email);
            $cmd->execute();

            if($cmd->rowCount() > 0)//email já existe no banco de dados
            {
                return false;
            } else //email nao foi encontrato
            {
                $cmd = $this->pdo->prepare("INSERT INTO pessoa (nome, telefone, email)
                    VALUES (:n, :t, :e)");
                $cmd->bindValue(":n",$nome);
                $cmd->bindValue(":t",$telefone);
                $cmd->bindValue(":e",$email);
                $cmd->execute();
                return true;
            }
        }

        public function excluirPessoa($id)
        {
            $cmd = $this->pdo->prepare("DELETE FROM pessoa WHERE id = :id");
            $cmd->bindValue(":id",$id);
            $cmd->execute();
        }

        //BUSCAR DADOS DE UMA PESSOA
        public function buscarDadosPessoas($id)
        {
            $cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
            $cmd->bindValue(":id",$id);
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return $res;
        }

        //ATUALIZAR DADOS NO BANCO DE DADOS
        public function atualizarDados($id, $nome, $telefone, $email)
        {
            //ANTES DE ATUALIZAR IRÁ VERIFICAR SE O EMAIL JÁ ESTA CADASTRADO
            $cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE email = :e");
            $cmd->bindValue(":e",$email);
            $cmd->execute();

            if($cmd->rowCount() > 0)//email já existe no banco de dados
            {
                return false;
            } else //email nao foi encontrato
            {

                $cmd = $this->pdo->prepare("UPDATE pessoa SET nome = :n, telefone = :t, email = :e WHERE id = :id");
                $cmd->bindValue(":n",$nome);
                $cmd->bindValue(":t",$telefone);
                $cmd->bindValue(":e",$email);
                $cmd->bindValue(":id",$id);
                $cmd->execute();
                return true;
            }
        }

    }

?>