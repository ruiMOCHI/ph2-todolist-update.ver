// 追加処理、更新処理、削除処理を addEventListener で指定
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('js-create-todo').addEventListener('click', createTodo);

const completeButtons = document.querySelectorAll(".js-complete-todo");
  completeButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const todoId = button.getAttribute("data-id");
      updateTodo(todoId);
    });
  });  //このコードは、特定のクラス（js-complete-todo）を持つすべての要素に対して、クリックイベントを追加する処理を行っています。具体的には、次のような手順で動作しています。
       //document.querySelectorAll(".js-complete-todo") を使用して、クラスが js-complete-todo であるすべての要素を取得します。これらの要素は、ToDoを完了させるためのボタンと関連付けられています。
       //forEach() メソッドを使用して、取得したすべての要素に対してループします。
       //各要素に対して、クリックイベントのリスナーが追加されます。クリックされた際に、updateTodo 関数が呼び出されます。この関数は、引数として ToDo の ID を取ります。
       //クリックされたボタンから、それに関連付けられている ToDo の ID を取得するために、button.getAttribute("data-id") を使用します。data-id 属性には、ToDo の ID が格納されています。
       //このコードを理解すると、deleteButtons 関数を作成する際に、同様の手順を踏んで削除ボタンにクリックイベントを追加することができます。そのため、deleteButtons 関数も同様のパターンに従っています。削除ボタンがクリックされた際に、関連付けられている ToDo の ID を取得し、それを使用して削除処理を実行します。
       //このような手法を採用することで、異なるボタンや要素に同様の動作を実装する際に、コードの再利用性を高めることができます。

const deleteButtons = document.querySelectorAll(".js-delete-todo");
deleteButtons.forEach((button) => {
  button.addEventListener('click',  () => {
    const todoId = button.parentNode.getAttribute('data-id'); //parentNodeは指定する親要素が正しいか注意
    const parentNode = button.parentNode;
    deleteTodo(todoId, parentNode);
  });
});

});


//追加処理
async function createTodo() {
    const todoInput = document.getElementById('js-todo-text'); /*別のが入るかも？*/
    const todoText = todoInput.value;

    try {
        const response = await fetch("../admin/create/index.php", {
            method: "POST",
            headers: {
              "Content-type": "application/x-www-form-urlencoded",
            },
            body: `todo-text=${todoText}`
          });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error("Error from server: " + errorText);
        }

        const data = await response.json();
        addTodoElement(todoText, data.id);

        todoInput.value = '';
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

const addTodoElement = (text, id) => {
  const template = document.getElementById('js-template').content.cloneNode(true);
  template.getElementById('js-todo-text').textContent = text; /*cloneNode() 関数を使って <template> 内のコンテンツをクローンし、新たな To-Do 要素の基礎部分を生成*/

  const todoElement = template.getElementById("js-todo-template");
  todoElement.setAttribute("data-id", id); //<li> 要素を取得し、data-id 属性に id を設定する（スタータス更新の処理で使います）

  const completeButton = template.getElementById('js-complete-todo-template');
  completeButton.setAttribute("data-id", id);
  completeButton.addEventListener('click', () => {
      updateTodo(id);
  }); //ステータス更新ボタンの要素を取得し、data-id 属性に id や イベントリスナーを設定する

  template.getElementById('js-edit-todo-template').href = `admin/edit/index.php?id=${id}&text=${text}`;
  //引数を用いて To-Do のテキストと編集用のリンクを設定する

  const deleteButton = template.getElementById('js-delete-todo-template');
  deleteButton.setAttribute('data-id', id);
  deleteButton.addEventListener('click', () => {
      deleteTodo(id, deleteButton.parentNode);
  }); //削除ボタンの要素を取得し、data-id 属性に id や イベントリスナーを設定する

  document.getElementById('js-todo-list').appendChild(template);
  //appendChild() 関数を使って新しい To-Do を元のリストに追加する
}

const updateTodoElement = (id, isCompleted) => {
  const todoElement = document.querySelector(`.js-todo[data-id="${id}"]`);

  if (todoElement) {
    const completeButton = todoElement.querySelector('.js-complete-todo');
    completeButton.textContent = isCompleted ? 'Undo' : 'Complete';
  }
}

//ステータス更新処理
async function updateTodo(id) {
  try {
    const response = await fetch("../admin/update/index.php", {
      method: 'POST',
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `toggle-id=${id}`
    })

    if(!response.ok) {
      const errorText = await response.text();
      throw new Error('Error from server: ' + errorText);
    }

    const data = await response.json();
    updateTodoElement(id, data.completed);

  } catch (error) {
    alert('Error: ' + error.message);
  }
}

// 削除処理
async function deleteTodo(id, element) {
    try {
      const response = await fetch("../admin/delete/index.php", {
        method: 'POST',
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `delete-id=${id}` /*delete-id? todo-id?*/
      });
  
      if (!response.ok) {
        const errorText = await response.text();
        throw new Error('Error from server: ' + errorText);
      }
  
      element.remove();
    } catch (error) {
      alert('Error: ' + error.message);
    }
  }