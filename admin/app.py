import mysql.connector
from colorama import Fore, Back, Style, init

# Inicializa o colorama
init(autoreset=True)

# Função para conectar ao banco de dados
def connect_db():
    return mysql.connector.connect(
        host="localhost",  # Endereço do servidor
        user="root",  # Usuário do banco de dados
        password="",  # Senha do banco de dados
        database="lst_db"  # Nome do banco de dados
    )

def view_data(cursor, table):
    cursor.execute(f"FLUSH TABLES {table}")  # Forçar o MySQL a liberar o cache
    cursor.execute(f"SELECT * FROM {table}")
    rows = cursor.fetchall()
    if rows:
        for row in rows:
            print(row)
    else:
        print(Fore.YELLOW + "Nenhum dado encontrado.")

# Função para editar dados
def edit_data(cursor, table, field, new_value, condition_field, condition_value, db):
    try:
        # Garantir que o valor da condição seja tratado corretamente, como inteiro, se necessário
        if condition_field == "id":  # Se for o campo id, convertê-lo para inteiro
            condition_value = int(condition_value)

        # Se o campo de edição for uma URL, por exemplo, tratá-la corretamente
        if isinstance(new_value, str):
            new_value = new_value.strip()  # Remover espaços em branco extras

        # Atualizar os dados
        cursor.execute(f"UPDATE {table} SET {field} = %s WHERE {condition_field} = %s", (new_value, condition_value))

        # Verificar se o dado foi alterado
        if cursor.rowcount > 0:
            print(Fore.GREEN + f"Dado atualizado na tabela {table} com {field} = {new_value} onde {condition_field} = {condition_value}")
        else:
            print(Fore.YELLOW + f"Nenhum dado encontrado para atualizar onde {condition_field} = {condition_value}")

        # Comitar a transação
        db.commit()

    except mysql.connector.Error as err:
        print(Fore.RED + f"Erro ao atualizar dados: {err}")

# Função para inserir dados
def insert_data(cursor, table, fields, values, db):
    cursor.execute(f"INSERT INTO {table} ({', '.join(fields)}) VALUES ({', '.join(['%s']*len(values))})", tuple(values))
    db.commit()
    print(Fore.CYAN + f"Registo inserido na tabela {table}")

    # Recarregar os dados após a inserção
    db.close()  # Fechar a conexão anterior
    db = connect_db()  # Criar uma nova conexão
    cursor = db.cursor()  # Criar um novo cursor
    print(Fore.CYAN + "Agora, veja os registros atualizados:")
    view_data(cursor, table)  # Exibe imediatamente os dados atualizados

# Função para deletar dados
def delete_data(cursor, table, condition_field, condition_value, db):
    cursor.execute(f"DELETE FROM {table} WHERE {condition_field} = %s", (condition_value,))
    db.commit()
    print(Fore.MAGENTA + f"Registo eliminado na tabela {table} onde {condition_field} = {condition_value}")

# Função para exibir o título ASCII
def print_title():
    title = r"""
.____    .__        __      _________                     
|    |   |__| ____ |  | __ /   _____/ ____ _____  ______  
|    |   |  |/    \|  |/ / \_____  \ /    \__  \ \____ \ 
|    |___|  |   |  \    <  /        \   |  \/ __ \|  |_> >
|_______ \__|___|  /__|_ \/_______  /___|  (____  /   __/ 
        \/       \/     \/        \/     \/     \/|__|    
        v1.0 @davidconcha              
    """
    print(Fore.YELLOW + title)

def menu():
    db = connect_db()
    cursor = db.cursor()

    print_title()  # Exibir o título ASCII

    while True:
        print(Fore.BLUE + "\nMenu:")
        print(Fore.GREEN + "1. Ver dados na tabela 'links'")
        print(Fore.GREEN + "2. Ver dados na tabela 'link_visits'")
        print(Fore.GREEN + "3. Editar dados na tabela 'links'")
        print(Fore.GREEN + "4. Inserir dados na tabela 'links'")
        print(Fore.GREEN + "5. Eliminar dados na tabela 'links'")
        print(Fore.RED + "6. Sair")

        choice = input(Fore.WHITE + "Escolha uma opção: ")

        if choice == '1':
            view_data(cursor, 'links')
        elif choice == '2':
            view_data(cursor, 'link_visits')
        elif choice == '3':
            field = input(Fore.YELLOW + "Qual campo deseja editar (ex: original_url): ")
            new_value = input(Fore.YELLOW + "Novo valor: ")
            condition_field = input(Fore.YELLOW + "Campo de condição (ex: id): ")
            condition_value = input(Fore.YELLOW + "Valor de condição: ")
            edit_data(cursor, 'links', field, new_value, condition_field, condition_value, db)
        elif choice == '4':
            fields = input(Fore.YELLOW + "Campos (separados por vírgula): ").split(',')
            values = input(Fore.YELLOW + "Valores (separados por vírgula): ").split(',')
            insert_data(cursor, 'links', fields, values, db)
            cursor.close()  # Fecha o cursor após inserir
            cursor = db.cursor()  # Reabre o cursor
            print(Fore.CYAN + "Dados inseridos. Agora você pode ver os novos registros.")
        elif choice == '5':
            condition_field = input(Fore.YELLOW + "Campo de condição (ex: id): ")
            condition_value = input(Fore.YELLOW + "Valor de condição: ")
            delete_data(cursor, 'links', condition_field, condition_value, db)
        elif choice == '6':
            db.commit()  # Confirmar todas as mudanças antes de sair
            cursor.close()
            db.close()
            print(Fore.RED + "Saindo...")
            break
        else:
            print(Fore.RED + "Opção inválida!")

# Executar o menu
menu()
