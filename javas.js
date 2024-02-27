const person1 = {
  fullName: function() {
    return this.firstName + " " + this.lastName;
  }
}

const person2 = {
  firstName:"John",
  lastName: "Doe",
}

// Використання методу fullName об'єкта person1 з контекстом person2
console.log(person1.fullName.call(person2)); // Виведе: "John Doe"
